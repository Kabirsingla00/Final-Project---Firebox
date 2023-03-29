<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('freightco_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'freightco_cf7_theme_setup9', 9 );
	function freightco_cf7_theme_setup9() {
		
		add_filter( 'freightco_filter_merge_scripts',	'freightco_cf7_merge_scripts');
		add_filter( 'freightco_filter_merge_styles',	'freightco_cf7_merge_styles' );

		if (freightco_exists_cf7()) {
			add_action( 'wp_enqueue_scripts',		'freightco_cf7_frontend_scripts', 1100 );
		}

		if (is_admin()) {
			add_filter( 'freightco_filter_tgmpa_required_plugins',	'freightco_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'freightco_cf7_tgmpa_required_plugins' ) ) {
	
	function freightco_cf7_tgmpa_required_plugins($list=array()) {
		if (freightco_storage_isset('required_plugins', 'contact-form-7')) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> freightco_storage_get_array('required_plugins', 'contact-form-7'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
			$params = array(
				'name' 		=> esc_html__('Contact Form 7 - Repeatable Fields', 'freightco'),
				'slug' 		=> 'cf7-repeatable-fields',
				'required' 	=> false
			);
			$list[] = $params;
		}
		return $list;
	}
}



// Check if cf7 installed and activated
if ( !function_exists( 'freightco_exists_cf7' ) ) {
	function freightco_exists_cf7() {
		return class_exists('WPCF7');
	}
}

// Enqueue custom scripts
if ( !function_exists( 'freightco_cf7_frontend_scripts' ) ) {
	
	function freightco_cf7_frontend_scripts() {
		if (freightco_exists_cf7()) {
			if (freightco_is_on(freightco_get_theme_option('debug_mode')) && freightco_get_file_dir('plugins/contact-form-7/contact-form-7.js')!='')
				wp_enqueue_script( 'freightco-cf7', freightco_get_file_url('plugins/contact-form-7/contact-form-7.js'), array('jquery'), null, true );
		}
	}
}
	
// Merge custom scripts
if ( !function_exists( 'freightco_cf7_merge_scripts' ) ) {
	
	function freightco_cf7_merge_scripts($list) {
		if (freightco_exists_cf7()) {
			$list[] = 'plugins/contact-form-7/contact-form-7.js';
		}
		return $list;
	}
}

// Merge custom styles
if ( !function_exists( 'freightco_cf7_merge_styles' ) ) {
	
	function freightco_cf7_merge_styles($list) {
		if (freightco_exists_cf7()) {
			$list[] = 'plugins/contact-form-7/_contact-form-7.scss';
		}
		return $list;
	}
}
?>