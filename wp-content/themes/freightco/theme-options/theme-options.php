<?php
/**
 * Theme Options, Color Schemes and Fonts utilities
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

// -----------------------------------------------------------------
// -- Create and manage Theme Options
// -----------------------------------------------------------------

// Theme init priorities:
// 2 - create Theme Options
if (!function_exists('freightco_options_theme_setup2')) {
	add_action( 'after_setup_theme', 'freightco_options_theme_setup2', 2 );
	function freightco_options_theme_setup2() {
		freightco_create_theme_options();
	}
}

// Step 1: Load default settings and previously saved mods
if (!function_exists('freightco_options_theme_setup5')) {
	add_action( 'after_setup_theme', 'freightco_options_theme_setup5', 5 );
	function freightco_options_theme_setup5() {
		freightco_storage_set('options_reloaded', false);
		freightco_load_theme_options();
	}
}

// Step 2: Load current theme customization mods
if (is_customize_preview()) {
	if (!function_exists('freightco_load_custom_options')) {
		add_action( 'wp_loaded', 'freightco_load_custom_options' );
		function freightco_load_custom_options() {
			if (!freightco_storage_get('options_reloaded')) {
				freightco_storage_set('options_reloaded', true);
				freightco_load_theme_options();
			}
		}
	}
}



// Load current values for each customizable option
if ( !function_exists('freightco_load_theme_options') ) {
	function freightco_load_theme_options() {
		$options = freightco_storage_get('options');
		$reset = (int) get_theme_mod('reset_options', 0);
		foreach ($options as $k=>$v) {
			if (isset($v['std'])) {
				$value = freightco_get_theme_option_std($k, $v['std']);
				if (!$reset) {
					if (isset($_GET[$k]))
						$value = wp_kses_data(wp_unslash($_GET[$k]));
					else {
						$default_value = -987654321;
						$tmp = get_theme_mod($k, $default_value);
						if ($tmp != $default_value) $value = $tmp;
					}
				}
				freightco_storage_set_array2('options', $k, 'val', $value);
				if ($reset) remove_theme_mod($k);
			}
		}
		if ($reset) {
			// Unset reset flag
			set_theme_mod('reset_options', 0);
			// Regenerate CSS with default colors and fonts
			freightco_customizer_save_css();
		} else {
			do_action('freightco_action_load_options');
		}
	}
}

// Override options with stored page/post meta
if ( !function_exists('freightco_override_theme_options') ) {
	add_action( 'wp', 'freightco_override_theme_options', 1 );
	function freightco_override_theme_options($query=null) {
		if (is_page_template('blog.php')) {
			freightco_storage_set('blog_archive', true);
			freightco_storage_set('blog_template', get_the_ID());
		}
		freightco_storage_set('blog_mode', freightco_detect_blog_mode());
		if (is_singular()) {
			freightco_storage_set('options_meta', get_post_meta(get_the_ID(), 'freightco_options', true));
		}
		do_action('freightco_action_override_theme_options');
	}
}

// Override options with stored page meta on 'Blog posts' pages
if ( !function_exists('freightco_blog_override_theme_options') ) {
	add_action( 'freightco_action_override_theme_options', 'freightco_blog_override_theme_options');
	function freightco_blog_override_theme_options() {
		global $wp_query;
		if (is_home() && !is_front_page() && !empty($wp_query->is_posts_page)) {
			if (($id = get_option('page_for_posts')) > 0)
				freightco_storage_set('options_meta', get_post_meta($id, 'freightco_options', true));
		}
	}
}


// Return 'std' value of the option, processed by special function (if specified)
if (!function_exists('freightco_get_theme_option_std')) {
	function freightco_get_theme_option_std($opt_name, $opt_std) {
		if (strpos($opt_std, '$freightco_')!==false) {
			$func = substr($opt_std, 1);
			if (function_exists($func)) {
				$opt_std = $func($opt_name);
			}
		}
		return $opt_std;
	}
}


// Return customizable option value
if (!function_exists('freightco_get_theme_option')) {
	function freightco_get_theme_option($name, $defa='', $strict_mode=false, $post_id=0) {
		$rez = $defa;
		$from_post_meta = false;

		if ($post_id > 0) {
			if (!freightco_storage_isset('post_options_meta', $post_id))
				freightco_storage_set_array('post_options_meta', $post_id, get_post_meta($post_id, 'freightco_options', true));
			if (freightco_storage_isset('post_options_meta', $post_id, $name)) {
				$tmp = freightco_storage_get_array('post_options_meta', $post_id, $name);
				if (!freightco_is_inherit($tmp)) {
					$rez = $tmp;
					$from_post_meta = true;
				}
			}
		}

		if (!$from_post_meta && freightco_storage_isset('options')) {

			$blog_mode = freightco_storage_get('blog_mode');

			if ( !freightco_storage_isset('options', $name) && (empty($blog_mode) || !freightco_storage_isset('options', $name.'_'.$blog_mode)) ) {
				$rez = $tmp = '_not_exists_';
				if (function_exists('trx_addons_get_option'))
					$rez = trx_addons_get_option($name, $tmp, false);
				if ($rez === $tmp) {
					if ($strict_mode) {
						// Translators: Add option's name to the output
						echo '<pre>' . esc_html(sprintf(__('Undefined option "%s" called from:', 'freightco'), $name));
						if (function_exists('dcs')) dcs();
						echo '</pre>';
						wp_die();
					} else
						$rez = $defa;
				}

			} else {

				$blog_mode_parent = $blog_mode=='post'
										? 'blog'
										: str_replace('_single', '', $blog_mode);

				// Override option from GET or POST for current blog mode
				if (!empty($blog_mode) && isset($_REQUEST[$name . '_' . $blog_mode])) {
					$rez = wp_kses_data(wp_unslash($_REQUEST[$name . '_' . $blog_mode]));

				// Override option from GET
				} else if (isset($_REQUEST[$name])) {
					$rez = wp_kses_data(wp_unslash($_REQUEST[$name]));

				// Override option from current page settings (if exists)
				} else if (freightco_storage_isset('options_meta', $name) && !freightco_is_inherit(freightco_storage_get_array('options_meta', $name))) {
					$rez = freightco_storage_get_array('options_meta', $name);

				// Override option from current blog mode settings: 'front', 'search', 'page', 'post', 'blog', etc. (if exists)
				} else if (!empty($blog_mode) && freightco_storage_isset('options', $name . '_' . $blog_mode, 'val') && !freightco_is_inherit(freightco_storage_get_array('options', $name . '_' . $blog_mode, 'val'))) {
					$rez = freightco_storage_get_array('options', $name . '_' . $blog_mode, 'val');

				// Override option for 'post' from 'blog' settings (if exists)
				// Also used for override 'xxx_single' on the 'xxx'
				// (for example, instead 'sidebar_courses_single' return option for 'sidebar_courses')
				} else if (!empty($blog_mode_parent) && $blog_mode!=$blog_mode_parent && freightco_storage_isset('options', $name . '_' . $blog_mode_parent, 'val') && !freightco_is_inherit(freightco_storage_get_array('options', $name . '_' . $blog_mode_parent, 'val'))) {
					$rez = freightco_storage_get_array('options', $name . '_' . $blog_mode_parent, 'val');

				// Get saved option value
				} else if (freightco_storage_isset('options', $name, 'val')) {
					$rez = freightco_storage_get_array('options', $name, 'val');

				// Get ThemeREX Addons option value
				} else if (function_exists('trx_addons_get_option')) {
					$rez = trx_addons_get_option($name, $defa, false);

				}
			}
		}
		return $rez;
	}
}


// Check if customizable option exists
if (!function_exists('freightco_check_theme_option')) {
	function freightco_check_theme_option($name) {
		return freightco_storage_isset('options', $name);
	}
}


// Return customizable option value, stored in the posts meta
if (!function_exists('freightco_get_theme_option_from_meta')) {
	function freightco_get_theme_option_from_meta($name, $defa='') {
		$rez = $defa;
		if (freightco_storage_isset('options_meta')) {
			if (freightco_storage_isset('options_meta', $name))
				$rez = freightco_storage_get_array('options_meta', $name);
			else
				$rez = 'inherit';
		}
		return $rez;
	}
}


// Get dependencies list from the Theme Options
if ( !function_exists('freightco_get_theme_dependencies') ) {
	function freightco_get_theme_dependencies() {
		$options = freightco_storage_get('options');
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}



//------------------------------------------------
// Save options
//------------------------------------------------
if (!function_exists('freightco_options_save')) {
	add_action('after_setup_theme', 'freightco_options_save', 4);
	function freightco_options_save() {

		if (!isset($_REQUEST['page']) || $_REQUEST['page']!='theme_options' || freightco_get_value_gp('freightco_nonce')=='') return;

		// verify nonce
		if ( !wp_verify_nonce( freightco_get_value_gp('freightco_nonce'), admin_url() ) ) {
			freightco_add_admin_message(esc_html__('Bad security code! Options are not saved!', 'freightco'), 'error', true);
			return;
		}

		// Check permissions
		if (!current_user_can('manage_options')) {
			freightco_add_admin_message(esc_html__('Manage options is denied for the current user! Options are not saved!', 'freightco'), 'error', true);
			return;
		}

		// Save options
		$options = freightco_storage_get('options');
		$values = get_theme_mods();
		$external_storages = array();
		foreach ($options as $k=>$v) {
			// Skip non-data options - sections, info, etc.
			if (!isset($v['std'])) continue;
			// Get option value from POST
			$value = isset($_POST['freightco_options_field_' . $k])
							? freightco_get_value_gp('freightco_options_field_' . $k)
							: ($v['type']=='checkbox' ? 0 : '');
			// Individual options processing
			if ($k == 'custom_logo' && !empty($value) && (int) $value == 0) {
				$value = attachment_url_to_postid(freightco_clear_thumb_size($value));
				if (empty($value)) $value = get_theme_mod($k);
			}
			// Save to the result array
			if (!empty($v['type']) && $v['type']!='hidden' && (empty($v['hidden']) || !$v['hidden']) && $value != freightco_get_theme_option_std($k, $v['std'])) {
				$values[$k] = $value;
			} else if (isset($values[$k])) {
				unset($values[$k]);
			}
			// External plugin's options
			if (!empty($v['options_storage'])) {
				if (!isset($external_storages[$v['options_storage']]))
					$external_storages[$v['options_storage']] = array();
				$external_storages[$v['options_storage']][$k] = $value;
			}
		}
		// Update options in the external storages
		foreach ($external_storages as $storage_name => $storage_values) {
			$storage = get_option($storage_name, false);
			if (is_array($storage)) {
				foreach ($storage_values as $k=>$v)
					$storage[$k] = $v;
				update_option($storage_name, apply_filters('freightco_filter_options_save', $storage, $storage_name));
			}
		}

		// Update Theme Mods (internal Theme Options)
		$stylesheet_slug = get_option('stylesheet');
		$values = apply_filters('freightco_filter_options_save', $values, 'theme_mods');
		update_option("theme_mods_{$stylesheet_slug}", $values);

		do_action('freightco_action_just_save_options', $values);

		// Store new schemes colors
		if (!empty($values['scheme_storage'])) {
			$schemes = freightco_unserialize($values['scheme_storage']);
			if (is_array($schemes) && count($schemes) > 0) 
				freightco_storage_set('schemes', $schemes);
		}
		
		// Store new fonts parameters
		$fonts = freightco_get_theme_fonts();
		foreach ($fonts as $tag=>$v) {
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				if (isset($values["{$tag}_{$css_prop}"])) $fonts[$tag][$css_prop] = $values["{$tag}_{$css_prop}"];
			}
		}
		freightco_storage_set('theme_fonts', $fonts);

		// Update ThemeOptions save timestamp
		$stylesheet_time = time();
		update_option("freightco_options_timestamp_{$stylesheet_slug}", $stylesheet_time);

		// Sinchronize theme options between child and parent themes
		if (freightco_get_theme_setting('duplicate_options') == 'both') {
			$theme_slug = get_option('template');
			if ($theme_slug != $stylesheet_slug) {
				freightco_customizer_duplicate_theme_options($stylesheet_slug, $theme_slug, $stylesheet_time);
			}
		}

		// Apply action - moved to the delayed state (see below) to load all enabled modules and apply changes after
		// Attention! Don't remove comment the line below!
		// Not need here: do_action('freightco_action_save_options');
		update_option('freightco_action', 'freightco_action_save_options');

		// Return result
		freightco_add_admin_message(esc_html__('Options are saved', 'freightco'));
		wp_redirect(get_admin_url(null, 'themes.php?page=theme_options'));
		exit();
	}
}


//-------------------------------------------------------
//-- Delayed action from previous session
//-- (after save options)
//-- to save new CSS, etc.
//-------------------------------------------------------
if ( !function_exists('freightco_do_delayed_action') ) {
	add_action( 'after_setup_theme', 'freightco_do_delayed_action' );
	function freightco_do_delayed_action() {
		if (($action = get_option('freightco_action')) != '') {
		    do_action($action);
			update_option('freightco_action', '');
		}
	}
}



// -----------------------------------------------------------------
// -- Theme Settings utilities
// -----------------------------------------------------------------

// Return internal theme setting value
if (!function_exists('freightco_get_theme_setting')) {
	function freightco_get_theme_setting($name) {
		if ( !freightco_storage_isset('settings', $name) ) {
			// Translators: Add setting's name to the output
			echo '<pre>' . esc_html(sprintf(__('Undefined setting "%s" called from:', 'freightco'), $name));
			if (function_exists('dcs')) dcs();
			echo '</pre>';
			wp_die();
		} else
			return freightco_storage_get_array('settings', $name);
	}
}

// Set theme setting
if ( !function_exists( 'freightco_set_theme_setting' ) ) {
	function freightco_set_theme_setting($option_name, $value) {
		if (freightco_storage_isset('settings', $option_name))
			freightco_storage_set_array('settings', $option_name, $value);
	}
}



// -----------------------------------------------------------------
// -- Color Schemes utilities
// -----------------------------------------------------------------

// Load saved values to the color schemes
if (!function_exists('freightco_load_schemes')) {
	add_action('freightco_action_load_options', 'freightco_load_schemes');
	function freightco_load_schemes() {
		$schemes = freightco_storage_get('schemes');
		$storage = freightco_unserialize(freightco_get_theme_option('scheme_storage'));
		if (is_array($storage) && count($storage) > 0)  {
			freightco_storage_set('schemes', $storage);
		}
	}
}

// Return specified color from current (or specified) color scheme
if ( !function_exists( 'freightco_get_scheme_color' ) ) {
	function freightco_get_scheme_color($color_name, $scheme = '') {
		if (empty($scheme)) $scheme = freightco_get_theme_option( 'color_scheme' );
		if (empty($scheme) || freightco_storage_empty('schemes', $scheme)) $scheme = 'default';
		$colors = freightco_storage_get_array('schemes', $scheme, 'colors');
		return $colors[$color_name];
	}
}

// Return colors from current color scheme
if ( !function_exists( 'freightco_get_scheme_colors' ) ) {
	function freightco_get_scheme_colors($scheme = '') {
		if (empty($scheme)) $scheme = freightco_get_theme_option( 'color_scheme' );
		if (empty($scheme) || freightco_storage_empty('schemes', $scheme)) $scheme = 'default';
		return freightco_storage_get_array('schemes', $scheme, 'colors');
	}
}

// Return colors from all schemes
if ( !function_exists( 'freightco_get_scheme_storage' ) ) {
	function freightco_get_scheme_storage($scheme = '') {
		return serialize(freightco_storage_get('schemes'));
	}
}

// Return schemes list
if ( !function_exists( 'freightco_get_list_schemes' ) ) {
	function freightco_get_list_schemes($prepend_inherit=false) {
		$list = array();
		$schemes = freightco_storage_get('schemes');
		if (is_array($schemes) && count($schemes) > 0) {
			foreach ($schemes as $slug => $scheme) {
				$list[$slug] = $scheme['title'];
			}
		}
		return $prepend_inherit ? freightco_array_merge(array('inherit' => esc_html__("Inherit", 'freightco')), $list) : $list;
	}
}

// Return all schemes, sorted by usage in the parameters 'xxx_scheme' on the current page
if ( !function_exists( 'freightco_get_sorted_schemes' ) ) {
	function freightco_get_sorted_schemes() {
		$params = freightco_storage_get('schemes_sorted');
		$schemes = freightco_storage_get('schemes');
		$rez = array();
		if (is_array($schemes)) {
			foreach ($params as $p) {
				if (!freightco_check_theme_option($p)) continue;
				$s = freightco_get_theme_option($p);
				if (!empty($s) && !freightco_is_inherit($s) && isset($schemes[$s])) {
					$rez[$s] = $schemes[$s];
					unset($schemes[$s]);
				}
			}
			if (count($schemes) > 0)
				$rez = array_merge($rez, $schemes);
		}
		return $rez;
	}
}


// -----------------------------------------------------------------
// -- Theme Fonts utilities
// -----------------------------------------------------------------

// Load saved values into fonts list
if (!function_exists('freightco_load_fonts')) {
	add_action('freightco_action_load_options', 'freightco_load_fonts');
	function freightco_load_fonts() {
		// Fonts to load when theme starts
		$load_fonts = array();
		for ($i=1; $i<=freightco_get_theme_setting('max_load_fonts'); $i++) {
			if (($name = freightco_get_theme_option("load_fonts-{$i}-name")) != '') {
				$load_fonts[] = array(
					'name'	 => $name,
					'family' => freightco_get_theme_option("load_fonts-{$i}-family"),
					'styles' => freightco_get_theme_option("load_fonts-{$i}-styles")
				);
			}
		}
		freightco_storage_set('load_fonts', $load_fonts);
		freightco_storage_set('load_fonts_subset', freightco_get_theme_option("load_fonts_subset"));
		
		// Font parameters of the main theme's elements
		$fonts = freightco_get_theme_fonts();
		foreach ($fonts as $tag=>$v) {
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$fonts[$tag][$css_prop] = freightco_get_theme_option("{$tag}_{$css_prop}");
			}
		}
		freightco_storage_set('theme_fonts', $fonts);
	}
}

// Return slug of the loaded font
if (!function_exists('freightco_get_load_fonts_slug')) {
	function freightco_get_load_fonts_slug($name) {
		return str_replace(' ', '-', $name);
	}
}

// Return load fonts parameter's default value
if (!function_exists('freightco_get_load_fonts_option')) {
	function freightco_get_load_fonts_option($option_name) {
		$rez = '';
		$parts = explode('-', $option_name);
		$load_fonts = freightco_storage_get('load_fonts');
		if ($parts[0] == 'load_fonts' && count($load_fonts) > $parts[1]-1 && isset($load_fonts[$parts[1]-1][$parts[2]])) {
			$rez = $load_fonts[$parts[1]-1][$parts[2]];
		}
		return $rez;
	}
}

// Return load fonts subset's default value
if (!function_exists('freightco_get_load_fonts_subset')) {
	function freightco_get_load_fonts_subset($option_name) {
		return freightco_storage_get('load_fonts_subset');
	}
}

// Return load fonts list
if (!function_exists('freightco_get_list_load_fonts')) {
	function freightco_get_list_load_fonts($prepend_inherit=false) {
		$list = array();
		$load_fonts = freightco_storage_get('load_fonts');
		if (is_array($load_fonts) && count($load_fonts) > 0) {
			foreach ($load_fonts as $font) {
				$list['"'.trim($font['name']).'"'.(!empty($font['family']) ? ','.trim($font['family']): '')] = $font['name'];
			}
		}
		return $prepend_inherit ? freightco_array_merge(array('inherit' => esc_html__("Inherit", 'freightco')), $list) : $list;
	}
}

// Return font settings of the theme specific elements
if ( !function_exists( 'freightco_get_theme_fonts' ) ) {
	function freightco_get_theme_fonts() {
		return freightco_storage_get('theme_fonts');
	}
}

// Return theme fonts parameter's default value
if (!function_exists('freightco_get_theme_fonts_option')) {
	function freightco_get_theme_fonts_option($option_name) {
		$rez = '';
		$parts = explode('_', $option_name);
		$theme_fonts = freightco_storage_get('theme_fonts');
		if (!empty($theme_fonts[$parts[0]][$parts[1]])) {
			$rez = $theme_fonts[$parts[0]][$parts[1]];
		}
		return $rez;
	}
}

// Update loaded fonts list in the each tag's parameter (p, h1..h6,...) after the 'load_fonts' options are loaded
if (!function_exists('freightco_update_list_load_fonts')) {
	add_action('freightco_action_load_options', 'freightco_update_list_load_fonts', 11);
	function freightco_update_list_load_fonts() {
		$theme_fonts = freightco_get_theme_fonts();
		$load_fonts = freightco_get_list_load_fonts(true);
		foreach ($theme_fonts as $tag=>$v) {
			freightco_storage_set_array2('options', $tag.'_font-family', 'options', $load_fonts);
		}
	}
}



// -----------------------------------------------------------------
// -- Other options utilities
// -----------------------------------------------------------------

// Return all vars from Theme Options with option 'customizer'
if ( !function_exists( 'freightco_get_theme_vars' ) ) {
	function freightco_get_theme_vars() {
		$options = freightco_storage_get('options');
		$vars = array();
		foreach ($options as $k=>$v) {
			if (!empty($v['customizer'])) $vars[$v['customizer']] = freightco_get_theme_option($k);
		}
		return $vars;
	}
}

// Return current theme-specific border radius for form's fields and buttons
if ( !function_exists( 'freightco_get_border_radius' ) ) {
	function freightco_get_border_radius() {
		$rad = str_replace(' ', '', freightco_get_theme_option('border_radius'));
		if (empty($rad)) $rad = 0;
		return freightco_prepare_css_value($rad); 
	}
}




// -----------------------------------------------------------------
// -- Theme Options page
// -----------------------------------------------------------------

if ( !function_exists('freightco_options_init_page_builder') ) {
	add_action( 'after_setup_theme', 'freightco_options_init_page_builder' );
	function freightco_options_init_page_builder() {
		if ( is_admin() ) {
			add_action('admin_enqueue_scripts',	'freightco_options_add_scripts');
		}
	}
}
	
// Load required styles and scripts for admin mode
if ( !function_exists( 'freightco_options_add_scripts' ) ) {
	
	function freightco_options_add_scripts() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && $screen->id == 'appearance_page_theme_options') {
			wp_enqueue_style( 'fontello-style',  freightco_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			wp_enqueue_style( 'wp-color-picker', false, array(), null);
			wp_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'freightco-options', freightco_get_file_url('theme-options/theme-options.js'), array('jquery'), null, true );
			wp_enqueue_script( 'colorpicker-colors', freightco_get_file_url('js/colorpicker/colors.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-colorpicker', freightco_get_file_url('js/colorpicker/jqColorPicker.js'), array('jquery'), null, true );
			wp_localize_script( 'freightco-options', 'freightco_dependencies', freightco_get_theme_dependencies() );
			wp_localize_script( 'freightco-options', 'freightco_color_schemes', freightco_storage_get('schemes') );
			wp_localize_script( 'freightco-options', 'freightco_simple_schemes', freightco_storage_get('schemes_simple') );
			wp_localize_script( 'freightco-options', 'freightco_sorted_schemes', freightco_storage_get('schemes_sorted') );
			wp_localize_script( 'freightco-options', 'freightco_theme_fonts', freightco_storage_get('theme_fonts') );
			wp_localize_script( 'freightco-options', 'freightco_theme_vars', freightco_get_theme_vars() );
			wp_localize_script( 'freightco-options', 'freightco_options_vars', apply_filters('freightco_filter_options_vars', array(
				'max_load_fonts' => freightco_get_theme_setting('max_load_fonts'),
				) ) );
		}
	}
}

// Add Theme Options item in the Appearance menu
if (!function_exists('freightco_options_add_menu_items')) {
	add_action( 'admin_menu', 'freightco_options_add_menu_items' );
	function freightco_options_add_menu_items() {
		if (!FREIGHTCO_THEME_FREE) {
			add_theme_page(
				esc_html__('Theme Options', 'freightco'),	//page_title
				esc_html__('Theme Options', 'freightco'),	//menu_title
				'manage_options',							//capability
				'theme_options',							//menu_slug
				'freightco_options_page_builder'			//callback
			);
		}
	}
}


// Build options page
if (!function_exists('freightco_options_page_builder')) {
	function freightco_options_page_builder() {
		?>
		<div class="freightco_options">
			<h2 class="freightco_options_title"><?php esc_html_e('Theme Options', 'freightco'); ?></h2>
			<?php freightco_show_admin_messages(); ?>
			<form id="freightco_options_form" action="#" method="post" enctype="multipart/form-data">
				<input type="hidden" name="freightco_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
				<?php freightco_options_show_fields(); ?>
				<div class="freightco_options_buttons">
					<input type="button" class="freightco_options_button_submit" value="<?php esc_attr_e('Save Options', 'freightco'); ?>">
				</div>
			</form>
		</div>
		<?php
	}
}


// Display all option's fields
if ( !function_exists('freightco_options_show_fields') ) {
	function freightco_options_show_fields($options=false) {
		if (empty($options)) $options = freightco_storage_get('options');
		$tabs_titles = $tabs_content = array();
		$last_panel = $last_section = $last_group = '';
		foreach ($options as $k=>$v) {
			// New tab
			if ($v['type']=='panel' || ($v['type']=='section' && empty($last_panel))) {
				if (!isset($tabs_titles[$k])) {
					$tabs_titles[$k] = $v['title'];
					$tabs_content[$k] = '';
				}
				if (!empty($last_group)) {
					$tabs_content[$last_section] .= '</div></div>';
					$last_group = '';
				}
				$last_section = $k;
				if ($v['type']=='panel') $last_panel = $k;

			// New group
			} else if ($v['type']=='group' || ($v['type']=='section' && !empty($last_panel))) {
				if (empty($last_group))
					$tabs_content[$last_section] = (!isset($tabs_content[$last_section]) ? '' : $tabs_content[$last_section]) 
													. '<div class="freightco_accordion freightco_options_groups">';
				else
					$tabs_content[$last_section] .= '</div>';
				$tabs_content[$last_section] .= '<h4 class="freightco_accordion_title freightco_options_group_title">' . esc_html($v['title']) . '</h4>'
												. '<div class="freightco_accordion_content freightco_options_group_content">';
				$last_group = $k;
			
			// End panel, section or group
			} else if (in_array($v['type'], array('group_end', 'section_end', 'panel_end'))) {
				if (!empty($last_group) && ($v['type'] != 'section_end' || empty($last_panel))) {
					$tabs_content[$last_section] .= '</div></div>';
					$last_group = '';
				}
				if ($v['type'] == 'panel_end') $last_panel = '';
				
			// Field's layout
			} else {
				$tabs_content[$last_section] = (!isset($tabs_content[$last_section]) ? '' : $tabs_content[$last_section]) 
												. freightco_options_show_field($k, $v);
			}
		}
		if (!empty($last_group)) {
			$tabs_content[$last_section] .= '</div></div>';
		}
		
		if (count($tabs_content) > 0) {
			// Remove empty sections
			foreach ($tabs_content as $k=>$v) {
				if (empty($v)) {
					unset($tabs_titles[$k]);
					unset($tabs_content[$k]);
				}
			}
			?>
			<div id="freightco_options_tabs" class="freightco_tabs <?php echo count($tabs_titles) > 1 ? 'with_tabs' : 'no_tabs'; ?>">
				<?php if (count($tabs_titles) > 1) { ?>
					<ul><?php
						$cnt = 0;
						foreach ($tabs_titles as $k=>$v) {
							$cnt++;
							?><li><a href="#freightco_options_section_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
						}
					?></ul>
				<?php
				}
				$cnt = 0;
				foreach ($tabs_content as $k=>$v) {
					$cnt++;
					?>
					<div id="freightco_options_section_<?php echo esc_attr($cnt); ?>" class="freightco_tabs_section freightco_options_section">
						<?php freightco_show_layout($v); ?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}


// Display single option's field
if ( !function_exists('freightco_options_show_field') ) {
	function freightco_options_show_field($name, $field, $post_type='') {

		$inherit_allow = !empty($post_type);
		$inherit_state = !empty($post_type) && isset($field['val']) && freightco_is_inherit($field['val']);
		
		$field_data_present = $field['type']!='info' || !empty($field['override']['desc']) || !empty($field['desc']);

		if (   ($field['type'] == 'hidden' && $inherit_allow) 	// Hidden field in the post meta (not in the root Theme Options)
			|| (!empty($field['hidden']) && !$inherit_allow)	// Field only for post meta in the root Theme Options
		   ) return '';
		
		if ($field['type'] == 'hidden') {

			$output = '<input type="hidden" name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr($field['val']).'"'
								. ' />';
		} else {
		
			$output = (!empty($field['class']) && strpos($field['class'], 'freightco_new_row')!==false 
						? '<div class="freightco_new_row_before"></div>'
						: '')
						. '<div class="freightco_options_item freightco_options_item_'.esc_attr($field['type'])
									. ($inherit_allow ? ' freightco_options_inherit_'.($inherit_state ? 'on' : 'off' ) : '')
									. (!empty($field['class']) ? ' '.esc_attr($field['class']) : '')
									. '">'
							. '<h4 class="freightco_options_item_title">'
								. esc_html($field['title'])
								. ($inherit_allow 
										? '<span class="freightco_options_inherit_lock" id="freightco_options_inherit_'.esc_attr($name).'"></span>'
										: '')
							. '</h4>'
							. ($field_data_present
								? '<div class="freightco_options_item_data">'
									. '<div class="freightco_options_item_field" data-param="'.esc_attr($name).'"'
										. (!empty($field['linked']) ? ' data-linked="'.esc_attr($field['linked']).'"' : '')
										. '>'
								: '');
		
			// Type 'checkbox'
			if ($field['type']=='checkbox') {
				$output .= '<label class="freightco_options_item_label">'
							. '<input type="checkbox" name="freightco_options_field_'.esc_attr($name).'" value="1"'
									.($field['val']==1 ? ' checked="checked"' : '')
									.' />'
							. esc_html($field['title'])
						. '</label>';
			
			// Type 'switch' (2 choises) or 'radio' (3+ choises)
			} else if (in_array($field['type'], array('switch', 'radio'))) {
				$field['options'] = apply_filters('freightco_filter_options_get_list_choises', $field['options'], $name);
				$first = true;
				foreach ($field['options'] as $k=>$v) {
					$output .= '<label class="freightco_options_item_label">'
								. '<input type="radio" name="freightco_options_field_'.esc_attr($name).'"'
										. ' value="'.esc_attr($k).'"'
										. ('#'.$field['val'] == '#'.$k || ($first && !isset($field['options'][$field['val']])) ? ' checked="checked"' : '')
										. ' />'
								. esc_html($v)
							. '</label>';
					$first = false;
				}

			// Type 'text' or 'time' or 'date'
			} else if (in_array($field['type'], array('text', 'time', 'date'))) {
				$output .= '<input type="text" name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />';
			
			// Type 'textarea'
			} else if ($field['type']=='textarea') {
				$output .= '<textarea name="freightco_options_field_'.esc_attr($name).'">'
								. esc_html(freightco_is_inherit($field['val']) ? '' : $field['val'])
							. '</textarea>';
			
			// Type 'text_editor'
			} else if ($field['type']=='text_editor') {
				$output .= '<input type="hidden" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_textarea(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />'
							. freightco_show_custom_field('freightco_options_field_'.esc_attr($name).'_tinymce',
														$field,
														freightco_is_inherit($field['val']) ? '' : $field['val']);

			// Type 'select'
			} else if ($field['type']=='select') {
				$field['options'] = apply_filters('freightco_filter_options_get_list_choises', $field['options'], $name);
				$output .= '<select size="1" name="freightco_options_field_'.esc_attr($name).'">';
				foreach ($field['options'] as $k=>$v) {
					$output .= '<option value="'.esc_attr($k).'"'.('#'.$field['val']=='#'.$k ? ' selected="selected"' : '').'>'.esc_html($v).'</option>';
				}
				$output .= '</select>';

			// Type 'image', 'media', 'video' or 'audio'
			} else if (in_array($field['type'], array('image', 'media', 'video', 'audio'))) {
				if ( (int) $field['val'] > 0 ) {
					$image = wp_get_attachment_image_src( $field['val'], 'full' );
					$field['val'] = $image[0];
				}
				$output .= (!empty($field['multiple'])
							? '<input type="hidden" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />'
							: '<input type="text" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />')
						. freightco_show_custom_field('freightco_options_field_'.esc_attr($name).'_button',
													array(
														'type'			 => 'mediamanager',
														'multiple'		 => !empty($field['multiple']),
														'data_type'		 => $field['type'],
														'linked_field_id'=> 'freightco_options_field_'.esc_attr($name)
													),
													freightco_is_inherit($field['val']) ? '' : $field['val']);

			// Type 'color'
			} else if ($field['type']=='color') {
				$output .= '<input type="text" id="freightco_options_field_'.esc_attr($name).'"'
								. ' class="freightco_color_selector"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr($field['val']).'"'
								. ' />';
			
			// Type 'icon'
			} else if ($field['type']=='icon') {
				$output .= '<input type="text" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />'
							. freightco_show_custom_field('freightco_options_field_'.esc_attr($name).'_button',
														array(
															'type'	 => 'icons',
															'button' => true,
															'icons'	 => true
														),
														freightco_is_inherit($field['val']) ? '' : $field['val']);
			
			// Type 'checklist'
			} else if ($field['type']=='checklist') {
				$output .= '<input type="hidden" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />'
							. freightco_show_custom_field('freightco_options_field_'.esc_attr($name).'_list',
														$field,
														freightco_is_inherit($field['val']) ? '' : $field['val']);
			
			// Type 'scheme_editor'
			} else if ($field['type']=='scheme_editor') {
				$output .= '<input type="hidden" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ' />'
							. freightco_show_custom_field('freightco_options_field_'.esc_attr($name).'_scheme',
														$field,
														freightco_unserialize($field['val']));
			
			// Type 'slider' || 'range'
			} else if (in_array($field['type'], array('slider', 'range'))) {
				$field['show_value'] = !isset($field['show_value']) || $field['show_value'];
				$output .= '<input type="'.(!$field['show_value'] ? 'hidden' : 'text').'" id="freightco_options_field_'.esc_attr($name).'"'
								. ' name="freightco_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr(freightco_is_inherit($field['val']) ? '' : $field['val']).'"'
								. ($field['show_value'] ? ' class="freightco_range_slider_value"' : '')
								. ' />'
							. freightco_show_custom_field('freightco_options_field_'.esc_attr($name).'_slider',
														$field,
														freightco_is_inherit($field['val']) ? '' : $field['val']);
				
			}
			
			$output .= ($inherit_allow
							? '<div class="freightco_options_inherit_cover'.(!$inherit_state ? ' freightco_hidden' : '').'">'
								. '<span class="freightco_options_inherit_label">' . esc_html__('Inherit', 'freightco') . '</span>'
								. '<input type="hidden" name="freightco_options_inherit_'.esc_attr($name).'"'
										. ' value="'.esc_attr($inherit_state ? 'inherit' : '').'"'
										. ' />'
								. '</div>'
							: '')
						. ($field_data_present ? '</div>' : '')
						. (!empty($field['override']['desc']) || !empty($field['desc'])
							? '<div class="freightco_options_item_description">'
								. (!empty($field['override']['desc']) 	// param 'desc' already processed with wp_kses()!
										? $field['override']['desc'] 
										: $field['desc'])
								. '</div>'
							: '')
					. ($field_data_present ? '</div>' : '')
				. '</div>';
		}
		return $output;
	}
}


// Show theme specific fields
function freightco_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		
		case 'mediamanager':
			wp_enqueue_media( );
			$title = empty($field['data_type']) || $field['data_type']=='image'
							? esc_html__( 'Choose Image', 'freightco')
							: esc_html__( 'Choose Media', 'freightco');
			$output .= '<input type="button"'
							. ' id="'.esc_attr($id).'"'
							. ' class="button mediamanager freightco_media_selector"'
							. '	data-param="' . esc_attr($id) . '"'
							. '	data-choose="'.esc_attr(!empty($field['multiple']) ? esc_html__( 'Choose Images', 'freightco') : $title).'"'
							. ' data-update="'.esc_attr(!empty($field['multiple']) ? esc_html__( 'Add to Gallery', 'freightco') : $title).'"'
							. '	data-multiple="'.esc_attr(!empty($field['multiple']) ? '1' : '0').'"'
							. '	data-type="'.esc_attr(!empty($field['data_type']) ? $field['data_type'] : 'image').'"'
							. '	data-linked-field="'.esc_attr($field['linked_field_id']).'"'
							. ' value="'
								. (!empty($field['multiple'])
										? (empty($field['data_type']) || $field['data_type']=='image'
											? esc_html__( 'Add Images', 'freightco')
											: esc_html__( 'Add Files', 'freightco')
											)
										: esc_html($title)
									)
								. '"'
							. '>';
			$output .= '<span class="freightco_options_field_preview">';
			$images = explode('|', $value);
			if (is_array($images)) {
				foreach ($images as $img)
					$output .= $img && !freightco_is_inherit($img)
							? '<span>'
									. (in_array(freightco_get_file_ext($img), array('gif', 'jpg', 'jpeg', 'png'))
											? '<img src="' . esc_url($img) . '" alt="'. esc_attr__('image', 'freightco') .'">'
											: '<a href="' . esc_attr($img) . '">' . esc_html(basename($img)) . '</a>'
										)
								. '</span>' 
							: '';
			}
			$output .= '</span>';
			break;

		case 'icons':
			$icons_type = !empty($field['style']) 
							? $field['style'] 
							: freightco_get_theme_setting('icons_type');
			if (empty($field['return']))
				$field['return'] = 'full';
			$freightco_icons = $icons_type=='images'
								? freightco_get_list_images()
								: freightco_array_from_list(freightco_get_list_icons());
			if (is_array($freightco_icons)) {
				if (!empty($field['button']))
					$output .= '<span id="'.esc_attr($id).'"'
									. ' class="freightco_list_icons_selector'
											. ($icons_type=='icons' && !empty($value) ? ' '.esc_attr($value) : '')
											.'"'
									. ' title="'.esc_attr__('Select icon', 'freightco').'"'
									. ' data-style="'.($icons_type=='images' ? 'images' : 'icons').'"'
									. ($icons_type=='images' && !empty($value) 
										? ' style="background-image: url('.esc_url($field['return']=='slug' 
																							? $freightco_icons[$value] 
																							: $value).');"' 
											: '')
								. '></span>';
				if (!empty($field['icons'])) {
					$output .= '<div class="freightco_list_icons">'
								. '<input type="text" class="freightco_list_icons_search" placeholder="'.esc_attr__('Search icon ...', 'freightco').'">';
					foreach($freightco_icons as $slug=>$icon) {
						$output .= '<span class="'.esc_attr($icons_type=='icons' ? $icon : $slug)
								. (($field['return']=='full' ? $icon : $slug) == $value ? ' freightco_list_active' : '')
								. '"'
								. ' title="'.esc_attr($slug).'"'
								. ' data-icon="'.esc_attr($field['return']=='full' ? $icon : $slug).'"'
								. ($icons_type=='images' ? ' style="background-image: url('.esc_url($icon).');"' : '')
								. '></span>';
					}
					$output .= '</div>';
				}
			}
			break;

		case 'checklist':
			if (!empty($field['sortable']))
				wp_enqueue_script('jquery-ui-sortable', false, array('jquery', 'jquery-ui-core'), null, true);
			$output .= '<div class="freightco_checklist freightco_checklist_'.esc_attr($field['dir'])
						. (!empty($field['sortable']) ? ' freightco_sortable' : '') 
						. '">';
			if (!is_array($value)) {
				if (!empty($value) && !freightco_is_inherit($value)) parse_str(str_replace('|', '&', $value), $value);
				else $value = array();
			}
			// Sort options by values order
			if (!empty($field['sortable']) && is_array($value)) {
				$field['options'] = freightco_array_merge($value, $field['options']);
			}
			foreach ($field['options'] as $k=>$v) {
				$output .= '<label class="freightco_checklist_item_label' 
								. (!empty($field['sortable']) ? ' freightco_sortable_item' : '') 
								. '">'
							. '<input type="checkbox" value="1" data-name="'.$k.'"'
								.( isset($value[$k]) && (int) $value[$k] == 1 ? ' checked="checked"' : '')
								.' />'
							. (substr($v, 0, 4)=='http' ? '<img src="'.esc_url($v).'">' : esc_html($v))
						. '</label>';
			}
			$output .= '</div>';
			break;

		case 'slider':
		case 'range':
			wp_enqueue_script('jquery-ui-slider', false, array('jquery', 'jquery-ui-core'), null, true);
			$is_range  = $field['type'] == 'range';
			$field_min = !empty($field['min']) ? $field['min'] : 0;
			$field_max = !empty($field['max']) ? $field['max'] : 100;
			$field_step= !empty($field['step']) ? $field['step'] : 1;
			$field_val = !empty($value) 
							? ($value . ($is_range && strpos($value, ',')===false ? ','.$field_max : ''))
							: ($is_range ? $field_min.','.$field_max : $field_min);
			$output .= '<div id="'.esc_attr($id).'"'
							. ' class="freightco_range_slider"'
							. ' data-range="' . esc_attr($is_range ? 'true' : 'min') . '"'
							. ' data-min="' . esc_attr($field_min) . '"'
							. ' data-max="' . esc_attr($field_max) . '"'
							. ' data-step="' . esc_attr($field_step) . '"'
							. '>'
							. '<span class="freightco_range_slider_label freightco_range_slider_label_min">'
								. esc_html($field_min)
							. '</span>'
							. '<span class="freightco_range_slider_label freightco_range_slider_label_max">'
								. esc_html($field_max)
							. '</span>';
			$values = explode(',', $field_val);
			for ($i=0; $i < count($values); $i++) {
				$output .= '<span class="freightco_range_slider_label freightco_range_slider_label_cur">'
								. esc_html($values[$i])
							. '</span>';
			}
			$output .= '</div>';
			break;

		case 'text_editor':
			if (function_exists('wp_enqueue_editor')) wp_enqueue_editor();
			ob_start();
			wp_editor( $value, $id, array(
				'default_editor' => 'tmce',
				'wpautop' => isset($field['wpautop']) ? $field['wpautop'] : false,
				'teeny' => isset($field['teeny']) ? $field['teeny'] : false,
				'textarea_rows' => isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : 10,
				'editor_height' => 16*(isset($field['rows']) && $field['rows'] > 1 ? (int) $field['rows'] : 10),
				'tinymce' => array(
					'resize'             => false,
					'wp_autoresize_on'   => false,
					'add_unload_trigger' => false
				)
			));
			$editor_html = ob_get_contents();
			ob_end_clean();
			$output .= '<div class="freightco_text_editor">' . $editor_html . '</div>';
			break;

			
		case 'scheme_editor':
			if (!is_array($value)) break;
			if (empty($field['colorpicker'])) $field['colorpicker'] = 'internal';
			$output .= '<div class="freightco_scheme_editor">';
			// Select scheme
			$output .= '<div class="freightco_scheme_editor_scheme">'
							. '<select class="freightco_scheme_editor_selector">';
			foreach ($value as $scheme=>$v)
				$output .= '<option value="' . esc_attr($scheme) . '">' . esc_html($v['title']) . '</option>';
			$output .= '</select>';
			// Scheme controls
			$output .= '<span class="freightco_scheme_editor_controls">'
							. '<span class="freightco_scheme_editor_control freightco_scheme_editor_control_reset" title="'.esc_attr__('Reset scheme', 'freightco').'"></span>'
							. '<span class="freightco_scheme_editor_control freightco_scheme_editor_control_copy" title="'.esc_attr__('Duplicate scheme', 'freightco').'"></span>'
							. '<span class="freightco_scheme_editor_control freightco_scheme_editor_control_delete" title="'.esc_attr__('Delete scheme', 'freightco').'"></span>'
						. '</span>'
					. '</div>';
			// Select type
			$output .= '<div class="freightco_scheme_editor_type">'
							. '<div class="freightco_scheme_editor_row">'
								. '<span class="freightco_scheme_editor_row_cell">'
									. esc_html__('Editor type', 'freightco')
								. '</span>'
								. '<span class="freightco_scheme_editor_row_cell freightco_scheme_editor_row_cell_span">'
									.'<label>'
										. '<input name="freightco_scheme_editor_type" type="radio" value="simple" checked="checked"> '
										. esc_html__('Simple', 'freightco')
									. '</label>'
									. '<label>'
										. '<input name="freightco_scheme_editor_type" type="radio" value="advanced"> '
										. esc_html__('Advanced', 'freightco')
									. '</label>'
								. '</span>'
							. '</div>'
						. '</div>';
			// Colors
			$groups = freightco_storage_get('scheme_color_groups');
			$colors = freightco_storage_get('scheme_color_names');
			$output .= '<div class="freightco_scheme_editor_colors">';
			foreach ($value as $scheme=>$v) {
				$output .= '<div class="freightco_scheme_editor_header">'
								. '<span class="freightco_scheme_editor_header_cell"></span>';
				foreach ($groups as $group_name=>$group_data) {
					$output .= '<span class="freightco_scheme_editor_header_cell" title="'.esc_attr($group_data['description']).'">' 
								. esc_html($group_data['title'])
								. '</span>';
				}
				$output .= '</div>';
				foreach ($colors as $color_name=>$color_data) {
					$output .= '<div class="freightco_scheme_editor_row">'
								. '<span class="freightco_scheme_editor_row_cell" title="'.esc_attr($color_data['description']).'">'
								. esc_html($color_data['title'])
								. '</span>';
					foreach ($groups as $group_name=>$group_data) {
						$slug = $group_name == 'main' 
									? $color_name 
									: str_replace('text_', '', "{$group_name}_{$color_name}");
						$output .= '<span class="freightco_scheme_editor_row_cell">'
									. (isset($v['colors'][$slug])
										? "<input type=\"text\" name=\"{$slug}\" class=\"".($field['colorpicker']=='tiny' ? 'tinyColorPicker' : 'iColorPicker')."\" value=\"".esc_attr($v['colors'][$slug])."\">"
										: ''
										)
									. '</span>';
					}
					$output .= '</div>';
				}
				break;
			}
			$output .= '</div>'
					. '</div>';
			break;
	}
	return apply_filters('freightco_filter_show_custom_field', $output, $id, $field, $value);
}


// Refresh data in the linked field
// according the main field value
if (!function_exists('freightco_refresh_linked_data')) {
	function freightco_refresh_linked_data($value, $linked_name) {
		if ($linked_name == 'parent_cat') {
			$tax = freightco_get_post_type_taxonomy($value);
			$terms = !empty($tax) ? freightco_get_list_terms(false, $tax) : array();
			$terms = freightco_array_merge(array(0 => esc_html__('- Select category -', 'freightco')), $terms);
			freightco_storage_set_array2('options', $linked_name, 'options', $terms);
		}
	}
}


// AJAX: Refresh data in the linked fields
if (!function_exists('freightco_callback_get_linked_data')) {
	add_action('wp_ajax_freightco_get_linked_data', 		'freightco_callback_get_linked_data');
	add_action('wp_ajax_nopriv_freightco_get_linked_data','freightco_callback_get_linked_data');
	function freightco_callback_get_linked_data() {
		if ( !wp_verify_nonce( freightco_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			wp_die();
		$chg_name = wp_kses_data(wp_unslash($_REQUEST['chg_name']));
		$chg_value = wp_kses_data(wp_unslash($_REQUEST['chg_value']));
		$response = array('error' => '');
		if ($chg_name == 'post_type') {
			$tax = freightco_get_post_type_taxonomy($chg_value);
			$terms = !empty($tax) ? freightco_get_list_terms(false, $tax) : array();
			$response['list'] = freightco_array_merge(array(0 => esc_html__('- Select category -', 'freightco')), $terms);
		}
		echo json_encode($response);
		wp_die();
	}
}
?>