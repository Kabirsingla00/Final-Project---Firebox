<?php
/* Cookie Information support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('freightco_wp_gdpr_compliance_theme_setup9')) {
	add_action( 'after_setup_theme', 'freightco_wp_gdpr_compliance_theme_setup9', 9 );
	function freightco_wp_gdpr_compliance_theme_setup9() {
		if (is_admin()) {
			add_filter( 'freightco_filter_tgmpa_required_plugins',		'freightco_wp_gdpr_compliance_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'freightco_wp_gdpr_compliance_tgmpa_required_plugins' ) ) {
	function freightco_wp_gdpr_compliance_tgmpa_required_plugins($list=array()) {
		if (freightco_storage_isset('required_plugins', 'wp-gdpr-compliance')) {
			$list[] = array(
				'name' 		=> freightco_storage_get_array('required_plugins', 'wp-gdpr-compliance'),
				'slug' 		=> 'wp-gdpr-compliance',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'freightco_exists_wp_gdpr_compliance' ) ) {
	function freightco_exists_wp_gdpr_compliance() {
//		Old way (before v.2.0)
//		return class_exists( 'WPGDPRC\WPGDPRC' );
//		New way (to avoid error in wp_gdpr_compliance autoloader)
//		Check constants:	before v.2.0						after v.2.0
        return defined( 'WP_GDPR_C_ROOT_FILE' ) || defined( 'WPGDPRC_ROOT_FILE' );
    }
}