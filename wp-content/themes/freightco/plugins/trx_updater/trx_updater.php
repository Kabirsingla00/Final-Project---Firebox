<?php
/* TRX Updater support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('freightco_trx_updater_theme_setup9')) {
	add_action( 'after_setup_theme', 'freightco_trx_updater_theme_setup9', 9 );
	function freightco_trx_updater_theme_setup9() {

		if (is_admin()) {
			add_filter( 'freightco_filter_tgmpa_required_plugins',			'freightco_trx_updater_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'freightco_trx_updater_tgmpa_required_plugins' ) ) {
	
	function freightco_trx_updater_tgmpa_required_plugins($list=array()) {
		if (freightco_storage_isset('required_plugins', 'trx_updater')) {
			$path = freightco_get_file_dir('plugins/trx_updater/trx_updater.zip');
			if (!empty($path) || freightco_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' => freightco_storage_get_array('required_plugins', 'trx_updater'),
					'slug' => 'trx_updater',
					'version' => '2.0.0',
					'source' => !empty($path) ? $path : 'upload://trx_updater.zip',
					'required' => false
				);
			}
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( !function_exists( 'freightco_exists_trx_updater' ) ) {
	function freightco_exists_trx_updater() {
		return function_exists( 'trx_updater_load_plugin_textdomain' );
	}
}
