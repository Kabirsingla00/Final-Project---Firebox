<?php
/* Elementor Builder support functions
------------------------------------------------------------------------------- */

if (!defined('FREIGHTCO_ELEMENTOR_PADDINGS')) define('FREIGHTCO_ELEMENTOR_PADDINGS', 15);

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('freightco_elm_theme_setup9')) {
	add_action( 'after_setup_theme', 'freightco_elm_theme_setup9', 9 );
	function freightco_elm_theme_setup9() {
		
		add_filter( 'freightco_filter_merge_styles',					'freightco_elm_merge_styles' );
		add_filter( 'freightco_filter_merge_styles_responsive', 		'freightco_elm_merge_styles_responsive');

		if (freightco_exists_elementor()) {
			add_action( 'init',										'freightco_elm_init_once', 3 );
			add_action( 'elementor/editor/before_enqueue_scripts',	'freightco_elm_editor_scripts');
			add_action( 'elementor/element/before_section_end',		'freightco_elm_add_color_scheme_control', 10, 3 );
			add_action( 'elementor/element/after_section_end',		'freightco_elm_add_page_options', 10, 3 );
			add_filter( 'elementor/page/settings/success_response_data', 'freightco_elm_page_options_save', 10, 3 );
			add_filter( 'freightco_filter_update_post_meta',			'freightco_elm_update_post_meta', 10, 2 );
			add_filter( 'elementor/general/settings/success_response_data', 'freightco_elm_general_options_save', 10, 3 );
			add_action( 'freightco_action_just_save_options',			'freightco_elm_just_save_options', 10, 1);
		}
		if (is_admin()) {
			add_filter( 'freightco_filter_tgmpa_required_plugins',	'freightco_elm_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'freightco_elm_tgmpa_required_plugins' ) ) {
	
	function freightco_elm_tgmpa_required_plugins($list=array()) {
		if (freightco_storage_isset('required_plugins', 'elementor')) {
			$list[] = array(
				'name' 		=> freightco_storage_get_array('required_plugins', 'elementor'),
				'slug' 		=> 'elementor',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if Elementor is installed and activated
if ( !function_exists( 'freightco_exists_elementor' ) ) {
	function freightco_exists_elementor() {
		return class_exists('Elementor\Plugin');
	}
}
	
// Merge custom styles
if ( !function_exists( 'freightco_elm_merge_styles' ) ) {
	
	function freightco_elm_merge_styles($list) {
		if (freightco_exists_elementor()) {
			$list[] = 'plugins/elementor/_elementor.scss';
		}
		return $list;
	}
}

// Merge responsive styles
if ( !function_exists( 'freightco_elm_merge_styles_responsive' ) ) {
	
	function freightco_elm_merge_styles_responsive($list) {
		if (freightco_exists_elementor()) {
			$list[] = 'plugins/elementor/_elementor-responsive.scss';
		}
		return $list;
	}
}


// Load required styles and scripts for Elementor Editor mode
if ( !function_exists( 'freightco_elm_editor_scripts' ) ) {
	
	function freightco_elm_editor_scripts() {
		// Load font icons
		wp_enqueue_style(  'fontello-style', freightco_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
		wp_enqueue_script( 'freightco-elementor-editor', freightco_get_file_url('plugins/elementor/elementor-editor.js'), array('jquery'), null, true );
	}
}


// Set Elementor's options at once
if (!function_exists('freightco_elm_init_once')) {
	
	function freightco_elm_init_once() {
		if (freightco_exists_elementor() && !get_option('freightco_setup_elementor_options', false)) {
			// Set theme-specific values to the Elementor's options
			update_option('elementor_disable_color_schemes', 'yes');
			update_option('elementor_disable_typography_schemes', 'yes');
			update_option('elementor_container_width', freightco_get_theme_option('page_width') + 2 * FREIGHTCO_ELEMENTOR_PADDINGS);	// Theme-specific width + paddings of the columns
			update_option('elementor_space_between_widgets', 0);
			update_option('elementor_stretched_section_container', '.page_wrap');
			update_option('elementor_page_title_selector', '.sc_layouts_title_caption');
			// Set flag to prevent change Elementor's options again
			update_option('freightco_setup_elementor_options', 1);
		}
	}
}


// Modify Elementor's options after the Theme Options saved
if (!function_exists('freightco_elm_just_save_options')) {
	
	function freightco_elm_just_save_options($values) {
		if (!empty($values['page_width']))
			update_option('elementor_container_width', $values['page_width'] + 2 * FREIGHTCO_ELEMENTOR_PADDINGS);	// Theme-specific width + paddings of the columns
	}
}


// Save General Options via AJAX from Elementor Editor
// (called when any option is changed)
if (!function_exists('freightco_elm_general_options_save')) {
	
	function freightco_elm_general_options_save($response_data, $post_id, $data) {
		if (!empty($data['elementor_container_width']) && $data['elementor_container_width'] != freightco_get_theme_option('page_width') + 2 * FREIGHTCO_ELEMENTOR_PADDINGS)
			set_theme_mod('page_width', $data['elementor_container_width'] - 2 * FREIGHTCO_ELEMENTOR_PADDINGS);	// // Elementor width - paddings of the columns
		return $response_data;
	}
}


// Add theme-specific controls to sections and columns
if (!function_exists('freightco_elm_add_color_scheme_control')) {
	
	function freightco_elm_add_color_scheme_control($element, $section_id, $args) {
		if ( is_object($element) ) {
			$el_name = $element->get_name();
			// Add color scheme selector
			if (apply_filters('freightco_filter_add_scheme_in_elements', 
							  (in_array($el_name, array('section', 'column')) && $section_id === 'section_advanced')
							  || ($el_name === 'common' && $section_id === '_section_style'),
							  $element, $section_id, $args)) {
				$element->add_control('scheme', array(
						'type' => \Elementor\Controls_Manager::SELECT,
						'label' => esc_html__("Color scheme", 'freightco'),
						'label_block' => true,
						'options' => freightco_array_merge(array('' => esc_html__('Inherit', 'freightco')), freightco_get_list_schemes()),
						'default' => '',
						'prefix_class' => 'scheme_'
						) );
			}
			// Add 'Override section width'
			if ($el_name == 'section' && $section_id === 'section_advanced') {
				$element->add_control('justify_columns', array(
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label' => esc_html__('Justify columns', 'freightco'),
						'description' => wp_kses_data( __("Stretch columns to align the left and right edges to the site content area", 'freightco') ),
						'label_off' => esc_html__( 'Off', 'freightco' ),
						'label_on' => esc_html__( 'On', 'freightco' ),
						'return_value' => 'justified',
						'prefix_class' => 'elementor-section-'
						) );
			}
			// Set default gap between columns to 'Extended'
			if ($el_name == 'section' && $section_id === 'section_layout') {
				$element->update_control('gap', array(
						'default' => 'extended'
						) );
			}
		}
	}
}


// Add tab with theme-specific Page Options to the Page Settings
//---------------------------------------------------------------
if (!function_exists('freightco_elm_add_page_options')) {
	
	function freightco_elm_add_page_options($element, $section_id, $args) {
		if ( is_object($element) ) {
			$el_name = $element->get_name();
			if ( in_array( $el_name, array( 'page-settings', 'post', 'wp-post', 'wp-page' ) ) && 'section_page_style' == $section_id ) {
				$post_id = get_the_ID();
				$post_type = get_post_type($post_id);
				if ($post_id > 0 && freightco_options_override_allow($post_type)) {
					// Load saved options 
					$meta = get_post_meta($post_id, 'freightco_options', true);
					$sections = array();
					global $FREIGHTCO_STORAGE;
					// Refresh linked data if this field is controller for the another (linked) field
					// Do this before show fields to refresh data in the $FREIGHTCO_STORAGE
					foreach ($FREIGHTCO_STORAGE['options'] as $k=>$v) {
						if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
						if (!empty($v['linked'])) {
							$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
							if (!empty($v['val']) && !freightco_is_inherit($v['val']))
								freightco_refresh_linked_data($v['val'], $v['linked']);
						}
					}
					// Collect fields to the tabs
					foreach ($FREIGHTCO_STORAGE['options'] as $k=>$v) {
						if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false || $v['type'] == 'hidden') continue;
						$sec = empty($v['override']['section']) ? esc_html__('General', 'freightco') : $v['override']['section'];
						if (!isset($sections[$sec])) {
							$sections[$sec] = array();
						}
						$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
						$sections[$sec][$k] = $v;
					}
					if (count($sections) > 0) {
						$cnt = 0;
						foreach ($sections as $sec => $v) {
							$cnt++;
							$element->start_controls_section(
								"section_theme_options_{$cnt}",
								[
									'label' => $sec,
									'tab' => \Elementor\Controls_Manager::TAB_ADVANCED 
								]
							);
							foreach ($v as $field_id => $params) {
								freightco_elm_add_page_options_field($element, $field_id, $params);
							}
							$element->end_controls_section();
						}
					}
				}
			}
		}
	}
}


// Add control for the specified field
if (!function_exists('freightco_elm_add_page_options_field')) {
	function freightco_elm_add_page_options_field($element, $id, $field) {
		$id_field = "freightco_options_field_{$id}";
		$id_override = "freightco_options_override_{$id}";
		// If fields is inherit
		$inherit_state = isset($field['val']) && freightco_is_inherit($field['val']);
		// Condition
		$condition = array();
		if (!empty($field['dependency'])) {
			foreach ($field['dependency'] as $k => $v) {
				$condition[substr($k, 0, 1) == '#' 
										? str_replace(array('#page_template', '#'), array('template', ''), $k) 
										: "freightco_options_field_{$k}"
									] = $v;
			}
		}
		// Inherit param
		$element->add_control( $id_override, [
			'label' => $field['title'],
			'label_block' => in_array($field['type'], array('media')),
			'description' => !empty($field['override']['desc']) ? $field['override']['desc'] : (!empty($field['desc']) ? $field['desc'] : ''),
			'separator' => 'before',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_off' => esc_html__( 'Inherit', 'freightco' ),
			'label_on' => esc_html__( 'Override', 'freightco' ),
			'return_value' => '1',
			'condition' => $condition
		] );

		// Field params
		$params = [
					'label' => esc_html__('New value', 'freightco'),
					'label_block' => in_array($field['type'], array('media', 'info')),
				];
		// Add dependency to params
		$condition[$id_override] = '1';
		$params['condition'] = $condition;
		// Type 'checkbox'
		if ($field['type'] == 'checkbox') {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Off', 'freightco' ),
						'label_on' => esc_html__( 'On', 'freightco' ),
						'return_value' => '1'
					]);
			$element->add_control( $id_field, $params );

		// Type 'switch' (2 choises) or 'radio' (3+ choises) or 'select'
		} else if (in_array($field['type'], array('switch', 'radio', 'select'))) {
			$field['options'] = apply_filters('freightco_filter_options_get_list_choises', $field['options'], $id);
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => $field['options']
					]);
			$element->add_control( $id_field, $params );

		// Type 'checklist', 'select2' and 'icon'
		} else if (in_array($field['type'], array('checklist', 'select2', 'icon'))) {
			$field['options'] = apply_filters('freightco_filter_options_get_list_choises', $field['options'], $id);
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::SELECT2,
						'options' => $field['options'],
						'multiple' => $field['type']=='checklist' || !empty($field['multiple'])
					]);
			$element->add_control( $id_field, $params );

		// Type 'text' or 'time'
		} else if (in_array($field['type'], array('text', 'time'))) {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::TEXT
					]);
			$element->add_control( $id_field, $params );

		// Type 'date'
		} else if ($field['type'] == 'date') {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::DATE_TIME
					]);
			$element->add_control( $id_field, $params );

		// Type 'textarea'
		} else if ($field['type'] == 'textarea') {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::TEXTAREA,
						'rows' => !empty($field['rows']) ? max(1, $field['rows']) : 5
					]);
			$element->add_control( $id_field, $params );

		// Type 'text_editor'
		} else if ($field['type'] == 'text_editor') {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::WYSIWYG
					]);
			$element->add_control( $id_field, $params );

		// Type 'media'
		} else if (in_array($field['type'], array('image', 'media', 'video', 'audio'))) {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::MEDIA,
						'default' => [
							'id' => !empty($field['val']) && !freightco_is_inherit($field['val']) ? attachment_url_to_postid(freightco_clear_thumb_size($field['val'])) : 0,
							'url' => !empty($field['val']) && !freightco_is_inherit($field['val']) ? $field['val'] : ''
						]
					]);
			$element->add_control( $id_field, $params );

		// Type 'color'
		} else if ($field['type'] == 'color') {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::COLOR,
					]);
			$element->add_control( $id_field, $params );

		// Type 'slider' or 'range'
		} else if (in_array($field['type'], array('slider', 'range'))) {
			$params = array_merge($params, [
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => !empty($field['val']) && !freightco_is_inherit($field['val']) ? $field['val'] : '',
							'unit' => 'px'
						],
						'range' => [
							'px' => [
								'min' => !empty($field['min']) ? $field['min'] : 0,
								'max' => !empty($field['max']) ? $field['max'] : 1000
							]
						]
					]);
			$element->add_control( $id_field, $params );

		}
	}
}


// Save Page Options via AJAX from Elementor Editor
// (called when any option is changed)
if (!function_exists('freightco_elm_page_options_save')) {
	
	function freightco_elm_page_options_save($response_data, $post_id, $data) {
		if ($post_id > 0 && is_array($data)) {
			$options = freightco_storage_get('options');
			$meta = get_post_meta($post_id, 'freightco_options', true);
			if (empty($meta)) $meta = array();
			foreach ($options as $k=>$v) {
				$id_field = "freightco_options_field_{$k}";
				$id_override = "freightco_options_override_{$k}";
				if (isset($data[$id_override])) {
					$meta[$k] = isset($data[$id_field]) 
									? (is_array($data[$id_field]) && isset($data[$id_field]['url'])
											? $data[$id_field]['url']
											: $data[$id_field]
											) 
									: (!empty($meta[$k]) && !freightco_is_inherit($meta[$k])
											? $meta[$k]
											: $v['std']
											);
				} else if (isset($meta[$k]))
					unset($meta[$k]);
			}
			update_post_meta( $post_id, 'freightco_options', apply_filters( 'freightco_filter_update_post_meta', $meta, $post_id ) );

			// Save separate meta options to search template pages
			if (get_post_type($post_id)=='page' && !empty($data['template']) && $data['template']=='blog.php') {
				update_post_meta($post_id, 'freightco_options_post_type', isset($meta['post_type']) ? $meta['post_type'] : 'post');
				update_post_meta($post_id, 'freightco_options_parent_cat', isset($meta['parent_cat']) ? $meta['parent_cat'] : 0);
			}
		}
		return $response_data;
	}
}


// Save Page Options when page is updated (saved) from WordPress Editor
if (!function_exists('freightco_elm_update_post_meta')) {
	
	function freightco_elm_update_post_meta($meta, $post_id) {
		if (doing_filter('save_post')) {
			$elm_meta = get_post_meta($post_id, '_elementor_page_settings', true);
			if (is_array($elm_meta)) {
				foreach ($elm_meta as $k=>$v) {
					if (strpos($k, 'freightco_options_') !== false)
						unset($elm_meta[$k]);
				}
			} else {
				$elm_meta = array();
			}
			$options = freightco_storage_get('options');
			foreach ($meta as $k => $v) {
				$elm_meta["freightco_options_field_{$k}"] = in_array($options[$k]['type'], array('image','video','audio','media'))
																? array(
																	'id' => attachment_url_to_postid(freightco_clear_thumb_size($v)),
																	'url' => $v
																	)
																: $v;
				$elm_meta["freightco_options_override_{$k}"] = '1';
			}
			update_post_meta($post_id, '_elementor_page_settings', apply_filters( 'freightco_filter_elementor_update_page_settings', $elm_meta, $post_id ));
		}
		return $meta;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (freightco_exists_elementor()) { require_once FREIGHTCO_THEME_DIR . 'plugins/elementor/elementor-styles.php'; }
?>