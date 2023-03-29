<?php
/**
 * Widget: Display Contacts info
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Load widget
if (!function_exists('trx_addons_widget_contacts_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_contacts_load' );
	function trx_addons_widget_contacts_load() {
		register_widget('trx_addons_widget_contacts');
	}
}

// Widget Class
class trx_addons_widget_contacts extends TRX_Addons_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_contacts', 'description' => esc_html__('Contacts - logo and short description, address, phone and email', 'trx_addons'));
		parent::__construct( 'trx_addons_widget_contacts', esc_html__('ThemeREX Contacts', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget($args, $instance) {

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
	
		$logo = isset($instance['logo']) ? $instance['logo'] : '';
		$logo_retina = isset($instance['logo_retina']) ? $instance['logo_retina'] : '';
		// Uncomment next section (remove false from the condition)
		// if you want to get logo from current theme (if parameter 'logo' is empty)
		if (false && empty($logo)) {
			$logo = apply_filters('trx_addons_filter_theme_logo', '');
			if (is_array($logo)) {
				$logo = !empty($logo['logo']) ? $logo['logo'] : '';
				$logo_retina = !empty($logo['logo_retina']) ? $logo['logo_retina'] : $logo_retina;
			}
		}
		if (!empty($logo)) {
			$logo = trx_addons_get_attachment_url($logo, 'full');
			if (!empty($logo)) {
				$attr = trx_addons_getimagesize($logo);
				$logo = '<img src="'.esc_url($logo).'" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
			}
			// Logo for Retina
			if (!empty($logo_retina) && trx_addons_get_retina_multiplier() > 1) {
				$logo_retina = trx_addons_get_attachment_url($logo_retina, 'full');
				if (!empty($logo_retina)) {
					$logo = '<img src="'.esc_url($logo_retina).'" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
			}
		}

		$description = isset($instance['description']) ? $instance['description'] : '';
		$content = isset($instance['content']) ? $instance['content'] : '';

		$address_label = isset($instance['address_label']) ? $instance['address_label'] : '';
		$address = isset($instance['address']) ? $instance['address'] : '';
		$address_url = isset($instance['address_url']) ? $instance['address_url'] : '';
		$phone_label = isset($instance['phone_label']) ? $instance['phone_label'] : '';
		$phone = isset($instance['phone']) ? $instance['phone'] : '';
		$phone_descr = isset($instance['phone_descr']) ? $instance['phone_descr'] : '';
		$phone2 = isset($instance['phone2']) ? $instance['phone2'] : '';
		$phone2_descr = isset($instance['phone2_descr']) ? $instance['phone2_descr'] : '';
		$email_label = isset($instance['email_label']) ? $instance['email_label'] : '';
		$email = isset($instance['email']) ? $instance['email'] : '';
		$columns = isset($instance['columns']) ? (int) $instance['columns'] : 0;
		$socials = isset($instance['socials']) ? (int) $instance['socials'] : 0;

		$googlemap = isset($instance['googlemap']) ? (int) $instance['googlemap'] : 0;
		$googlemap_height = !empty($instance['googlemap_height']) ? $instance['googlemap_height'] : 130;
		$googlemap_position = isset($instance['googlemap_position']) ? $instance['googlemap_position'] : 'top';

		trx_addons_get_template_part(TRX_ADDONS_PLUGIN_WIDGETS . 'contacts/tpl.default.php',
									'trx_addons_args_widget_contacts', 
									apply_filters('trx_addons_filter_widget_args',
												array_merge($args, compact('title', 'logo', 'logo_retina', 'description', 'content',
																			'email','email_label', 'columns', 'address_label','address','address_url', 'phone_label','phone', 'phone_descr','phone2','phone2_descr', 'socials', 'googlemap',
																			'googlemap_height', 'googlemap_position')),
												$instance, 'trx_addons_widget_contacts')
									);
	}

	// Update the widget settings.
	function update($new_instance, $instance) {
		$instance = array_merge($instance, $new_instance);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['logo'] = strip_tags($new_instance['logo']);
		$instance['logo_retina'] = strip_tags($new_instance['logo_retina']);
		$instance['description'] = wp_kses_data($new_instance['description']);
		$instance['address_label'] = wp_kses_data($new_instance['address_label']);
		$instance['address'] = wp_kses_data($new_instance['address']);
		$instance['address_url'] = wp_kses_data($new_instance['address_url']);
		$instance['phone_label'] = wp_kses_data($new_instance['phone_label']);
		$instance['phone'] = wp_kses_data($new_instance['phone']);
		$instance['phone_descr'] = wp_kses_data($new_instance['phone_descr']);
		$instance['phone2'] = wp_kses_data($new_instance['phone2']);
		$instance['phone2_descr'] = wp_kses_data($new_instance['phone2_descr']);
		$instance['email_label'] = wp_kses_data($new_instance['email_label']);
		$instance['email'] = wp_kses_data($new_instance['email']);
		$instance['columns'] = isset( $new_instance['columns'] ) && (int)$new_instance['columns'] > 0 ? 1 : 0;
		$instance['socials'] = isset( $new_instance['socials'] ) && (int)$new_instance['socials'] > 0 ? 1 : 0;
		$instance['googlemap'] = isset( $new_instance['googlemap'] ) && (int)$new_instance['googlemap'] > 0 ? 1 : 0;
		$instance['googlemap_height'] = strip_tags($new_instance['googlemap_height']);
		$instance['googlemap_position'] = strip_tags($new_instance['googlemap_position']);
		return apply_filters('trx_addons_filter_widget_args_update', $instance, $new_instance, 'trx_addons_widget_contacts');
	}

	// Displays the widget settings controls on the widget panel.
	function form($instance) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, apply_filters('trx_addons_filter_widget_args_default', array(
			'title' => '',
			'logo' => '',
			'logo_retina' => '',
			'description' => '',
			'address_label' => '',
			'address' => '',
			'address_url' => '',
			'phone_label' => '',
			'phone' => '',
			'phone_descr' => '',
			'phone2' => '',
			'phone2_descr' => '',
			'email_label' => '',
			'email' => '',
			'columns' => 0,
			'socials' => 0,
			'googlemap' => 0,
			'googlemap_height' => 140,
			'googlemap_position' => 'top',
			), 'trx_addons_widget_contacts')
		);
		
		do_action('trx_addons_action_before_widget_fields', $instance, 'trx_addons_widget_contacts');
		
		$this->show_field(array('name' => 'title',
								'title' => __('Widget title:', 'trx_addons'),
								'value' => $instance['title'],
								'type' => 'text'));
		
		do_action('trx_addons_action_after_widget_title', $instance, 'trx_addons_widget_contacts');

		$this->show_field(array('name' => 'logo',
								'title' => __('Logo:', 'trx_addons'),
								'value' => $instance['logo'],
								'type' => 'image'));

		$this->show_field(array('name' => 'logo_retina',
								'title' => __('Logo for Retina:', 'trx_addons'),
								'value' => $instance['logo_retina'],
								'type' => 'image'));

		$this->show_field(array('name' => 'description',
								'title' => __('Short description about user:', 'trx_addons'),
								'value' => $instance['description'],
								'type' => 'textarea'));

		$this->show_field(array('name' => 'address_label',
								'title' => __('Address label:', 'trx_addons'),
								'value' => $instance['address_label'],
								'type' => 'text'));

		$this->show_field(array('name' => 'address',
								'title' => __('Address:', 'trx_addons'),
								'value' => $instance['address'],
								'type' => 'text'));

		$this->show_field(array('name' => 'address_url',
								'title' => __('Address URL:', 'trx_addons'),
								'value' => $instance['address_url'],
								'type' => 'text'));

		$this->show_field(array('name' => 'phone_label',
								'title' => __('Phone label:', 'trx_addons'),
								'value' => $instance['phone_label'],
								'type' => 'text'));

		$this->show_field(array('name' => 'phone',
								'title' => __('Phone:', 'trx_addons'),
								'value' => $instance['phone'],
								'type' => 'text'));
		
		$this->show_field(array('name' => 'phone_descr',
								'title' => __('Phone description:', 'trx_addons'),
								'value' => $instance['phone_descr'],
								'type' => 'text'));
		
		$this->show_field(array('name' => 'phone2',
								'title' => __('Phone:', 'trx_addons'),
								'value' => $instance['phone'],
								'type' => 'text'));

		$this->show_field(array('name' => 'phone2_descr',
								'title' => __('Phone description:', 'trx_addons'),
								'value' => $instance['phone'],
								'type' => 'text'));

		$this->show_field(array('name' => 'email_label',
								'title' => __('E-mail label:', 'trx_addons'),
								'value' => $instance['email_label'],
								'type' => 'text'));

		$this->show_field(array('name' => 'email',
								'title' => __('E-mail:', 'trx_addons'),
								'value' => $instance['email'],
								'type' => 'text'));

		do_action('trx_addons_action_after_widget_fields', $instance, 'trx_addons_widget_contacts');
	}
}

	
// Merge widget specific styles into single stylesheet
if ( !function_exists( 'trx_addons_widget_contacts_merge_styles' ) ) {
	add_filter("trx_addons_filter_merge_styles", 'trx_addons_widget_contacts_merge_styles');
	function trx_addons_widget_contacts_merge_styles($list) {
		$list[] = TRX_ADDONS_PLUGIN_WIDGETS . 'contacts/_contacts.scss';
		return $list;
	}
}



// trx_widget_contacts
//-------------------------------------------------------------
/*
[trx_widget_contacts id="unique_id" title="Widget title" logo="image_url" logo_retina="image_url" description="short description" address="Address string" phone="Phone" email="Email" socials="0|1"]
*/
if ( !function_exists( 'trx_addons_sc_widget_contacts' ) ) {
	function trx_addons_sc_widget_contacts($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_widget_contacts', $atts, array(
			// Individual params
			"title" => "",
			"logo" => "",
			"logo_retina" => "",
			"description" => "",
			"googlemap" => 0,
			"googlemap_height" => 140,
			"googlemap_position" => "top",
			"address_label" => "",
			"address" => "",
			"address_url" => "",
			"phone_label" => "",
			"phone" => "",
			"phone_descr" => "",
			"phone2" => "",
			"phone2_descr" => "",
			"email_label" => "",
			"email" => "",
			"columns" => 0,
			"socials" => 0,
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		if ($atts['columns']=='') $atts['columns'] = 0;
		if ($atts['socials']=='') $atts['socials'] = 0;
		if ($atts['googlemap']=='') $atts['googlemap'] = 0;
		extract($atts);
		$atts['content'] = do_shortcode($content);
		$type = 'trx_addons_widget_contacts';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_contacts' 
								. (trx_addons_exists_vc() ? ' vc_widget_contacts wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
			ob_start();
			the_widget( $type, $atts, trx_addons_prepare_widgets_args($id ? $id.'_widget' : 'widget_contacts', 'widget_contacts') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('trx_addons_sc_output', $output, 'trx_widget_contacts', $atts, $content);
	}
}


// Add [trx_widget_contacts] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_contacts_add_in_vc')) {
	function trx_addons_sc_widget_contacts_add_in_vc() {
		
		add_shortcode("trx_widget_contacts", "trx_addons_sc_widget_contacts");
		
		if (!trx_addons_exists_vc()) return;
		
		vc_lean_map("trx_widget_contacts", 'trx_addons_sc_widget_contacts_add_in_vc_params');
		class WPBakeryShortCode_Trx_Widget_Contacts extends WPBakeryShortCodesContainer {}
	}
	add_action('init', 'trx_addons_sc_widget_contacts_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_widget_contacts_add_in_vc_params')) {
	function trx_addons_sc_widget_contacts_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_contacts",
				"name" => esc_html__("Contacts", 'trx_addons'),
				"description" => wp_kses_data( __("Insert widget with logo, short description and contacts", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_contacts',
				"class" => "trx_widget_contacts",
				"content_element" => true,
				'is_container' => true,
				'as_child' => array('except' => 'trx_widget_contacts'),
				"js_view" => 'VcTrxAddonsContainerView',	//'VcColumnView',
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "title",
							"heading" => esc_html__("Widget title", 'trx_addons'),
							"description" => wp_kses_data( __("Title of the widget", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "logo",
							"heading" => esc_html__("Logo", 'trx_addons'),
							"description" => wp_kses_data( __("Select or upload image or write URL from other site for site's logo.", 'trx_addons') ),
							"type" => "attach_image"
						),
						array(
							"param_name" => "logo_retina",
							"heading" => esc_html__("Logo Retina", 'trx_addons'),
							"description" => wp_kses_data( __("Select or upload image or write URL from other site: site's logo for the Retina display.", 'trx_addons') ),
							"type" => "attach_image"
						),
						array(
							"param_name" => "description",
							"heading" => esc_html__("Description", 'trx_addons'),
							"description" => wp_kses_data( __("Short description about user. If empty - get description of the first registered blog user", 'trx_addons') ),
							"type" => "textarea"
						),
						array(
							"param_name" => "address",
							"heading" => esc_html__("Address", 'trx_addons'),
							"description" => wp_kses_data( __("Address string. Use '|' to split this string on two parts", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "phone",
							"heading" => esc_html__("Phone", 'trx_addons'),
							"description" => wp_kses_data( __("Your phone", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "email",
							"heading" => esc_html__("E-mail", 'trx_addons'),
							"description" => wp_kses_data( __("Your e-mail address", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_contacts');
	}
}




// Elementor Widget
//------------------------------------------------------
if (!function_exists('trx_addons_sc_widget_contacts_add_in_elementor')) {
	add_action( 'elementor/widgets/widgets_registered', 'trx_addons_sc_widget_contacts_add_in_elementor' );
	function trx_addons_sc_widget_contacts_add_in_elementor() {
		
		if (!class_exists('TRX_Addons_Elementor_Widget')) return;	

		class TRX_Addons_Elementor_Widget_Contacts extends TRX_Addons_Elementor_Widget {

			/**
			 * Widget base constructor.
			 *
			 * Initializing the widget base class.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @param array      $data Widget data. Default is an empty array.
			 * @param array|null $args Optional. Widget default arguments. Default is null.
			 */
			public function __construct( $data = [], $args = null ) {
				parent::__construct( $data, $args );
				$this->add_plain_params([
					'logo' => 'url',
					'logo_retina' => 'url',
					'googlemap_height' => 'size+unit'
				]);
			}

			/**
			 * Retrieve widget name.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget name.
			 */
			public function get_name() {
				return 'trx_widget_contacts';
			}

			/**
			 * Retrieve widget title.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget title.
			 */
			public function get_title() {
				return __( 'Widget: Contacts', 'trx_addons' );
			}

			/**
			 * Retrieve widget icon.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget icon.
			 */
			public function get_icon() {
				return 'eicon-image-box';
			}

			/**
			 * Retrieve the list of categories the widget belongs to.
			 *
			 * Used to determine where to display the widget in the editor.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return array Widget categories.
			 */
			public function get_categories() {
				return ['trx_addons-widgets'];
			}

			/**
			 * Register widget controls.
			 *
			 * Adds different input fields to allow the user to change and customize the widget settings.
			 *
			 * @since 1.6.41
			 * @access protected
			 */
			protected function register_controls() {
				$this->start_controls_section(
					'section_sc_contacts',
					[
						'label' => __( 'Widget: Contacts', 'trx_addons' ),
					]
				);
				
				$this->add_control(
					'title',
					[
						'label' => __( 'Title', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Widget title", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'logo',
					[
						'label' => __( 'Logo', 'trx_addons' ),
						'label_block' => true,
						'type' => \Elementor\Controls_Manager::MEDIA,
						'default' => [
							'url' => '',
						],
					]
				);

				$this->add_control(
					'logo_retina',
					[
						'label' => __( 'Logo for Retina', 'trx_addons' ),
						'label_block' => true,
						'type' => \Elementor\Controls_Manager::MEDIA,
						'default' => [
							'url' => '',
						],
					]
				);

				$this->add_control(
					'heading_description',
					[
						'label' => __( 'Description', 'elementor' ),
						'description' => wp_kses_data( __("Short description", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				
				$this->add_control(
					'description',
					[
						'label' => '',
						'label_block' => true,
						'show_label' => false,
						'type' => \Elementor\Controls_Manager::WYSIWYG,
						'default' => ''
					]
				);

				$this->end_controls_section();

				$this->start_controls_section(
					'section_sc_contacts_address',
					[
						'label' => __( 'Contacts', 'trx_addons' ),
					]
				);

				$this->add_control(
					'address_label',
					[
						'label' => __( 'Address label', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Address label", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'address',
					[
						'label' => __( 'Address', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Address", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'address_url',
					[
						'label' => __( 'Address URL', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Address url", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'phone_label',
					[
						'label' => __( 'Phone label', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Phone label", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'phone',
					[
						'label' => __( 'Phone', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Phone", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'phone_descr',
					[
						'label' => __( 'Phone description', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Phone description", 'trx_addons' ),
						'default' => ''
					]
				);
				
				$this->add_control(
					'phone2',
					[
						'label' => __( 'Phone', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Phone", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'phone2_descr',
					[
						'label' => __( 'Phone description', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Phone description", 'trx_addons' ),
						'default' => ''
					]
				);
				
				$this->add_control(
					'email_label',
					[
						'label' => __( 'Email label', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "Email label", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->add_control(
					'email',
					[
						'label' => __( 'E-mail', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( "E-mail", 'trx_addons' ),
						'default' => ''
					]
				);

				$this->end_controls_section();


			}

			/**
			 * Render widget's template for the editor.
			 *
			 * Written as a Backbone JavaScript template and used to generate the live preview.
			 *
			 * @since 1.6.41
			 * @access protected
			 */
			protected function content_template() {
				trx_addons_get_template_part(TRX_ADDONS_PLUGIN_WIDGETS . "contacts/tpe.contacts.php",
										'trx_addons_args_widget_contacts',
										array('element' => $this)
									);
			}
		}
		
		// Register widget
		\Elementor\Plugin::$instance->widgets_manager->register_widget_type( new TRX_Addons_Elementor_Widget_Contacts() );
	}
}
?>