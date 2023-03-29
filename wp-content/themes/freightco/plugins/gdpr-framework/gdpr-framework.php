<?php
/* GDPR Framework support functions
------------------------------------------------------------------------------- */



// Filter to add in the required plugins list
if ( !function_exists( 'freightco_gdpr_tgmpa_required_plugins' ) ) {
	add_filter('freightco_filter_tgmpa_required_plugins',	'freightco_gdpr_tgmpa_required_plugins');
	function freightco_gdpr_tgmpa_required_plugins($list=array()) {
		if (freightco_storage_isset('required_plugins', 'gdpr-framework')) {
			$list[] = array(
					'name' 		=> freightco_storage_get_array('required_plugins', 'gdpr-framework'),
					'slug' 		=> 'gdpr-framework',
					'required' 	=> false
			);
		}
		return $list;
	}
}



// Check if cf7 installed and activated
if ( !function_exists( 'freightco_exists_gdpr' ) ) {
	function freightco_exists_gdpr() {
		return defined('GDPR_FRAMEWORK_VERSION');
	}
}
?>