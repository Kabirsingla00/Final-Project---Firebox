<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('freightco_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'freightco_revslider_theme_setup9', 9 );
	function freightco_revslider_theme_setup9() {

		add_filter( 'freightco_filter_merge_styles',				'freightco_revslider_merge_styles' );
		
		if (is_admin()) {
			add_filter( 'freightco_filter_tgmpa_required_plugins','freightco_revslider_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'freightco_revslider_tgmpa_required_plugins' ) ) {
	
	function freightco_revslider_tgmpa_required_plugins($list=array()) {
		if (freightco_storage_isset('required_plugins', 'revslider')) {
			$path = freightco_get_file_dir('plugins/revslider/revslider.zip');
			if (!empty($path) || freightco_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> freightco_storage_get_array('required_plugins', 'revslider'),
					'slug' 		=> 'revslider',
					'version'	=> '6.5.31',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'freightco_exists_revslider' ) ) {
	function freightco_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
	
// Merge custom styles
if ( !function_exists( 'freightco_revslider_merge_styles' ) ) {
	
	function freightco_revslider_merge_styles($list) {
		if (freightco_exists_revslider()) {
			$list[] = 'plugins/revslider/_revslider.scss';
		}
		return $list;
	}
}
?>