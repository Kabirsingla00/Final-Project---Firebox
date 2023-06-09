<?php
/**
 * Plugin support: WPML (Importer support)
 *
 * @package ThemeREX Addons
 * @since v1.6.38
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_wpml_importer_required_plugins' ) ) {
	add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_wpml_importer_required_plugins', 10, 2 );
	function trx_addons_wpml_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'sitepress-multilingual-cms')!==false && !trx_addons_exists_wpml() )
			$not_installed .= '<br>' . esc_html__('WPML - Sitepress Multilingual CMS', 'trx_addons');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_wpml_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'trx_addons_wpml_importer_set_options' );
	function trx_addons_wpml_importer_set_options($options=array()) {
		if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $options['required_plugins']) ) {
			$options['additional_options'][] = 'icl_%';
			$options['additional_options'][] = 'wpml_%';
		}
		if (is_array($options['files']) && count($options['files']) > 0) {
			foreach ($options['files'] as $k => $v) {
				$options['files'][$k]['file_with_sitepress-multilingual-cms'] = str_replace('name.ext', 'sitepress-multilingual-cms.txt', $v['file_with_']);
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( !function_exists( 'trx_addons_wpml_importer_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options',	'trx_addons_wpml_importer_check_options', 10, 4 );
	function trx_addons_wpml_importer_check_options($allow, $k, $v, $options) {
		if ($allow && $k == 'icl_sitepress_settings') {
			$allow = trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $options['required_plugins']);
		}
		return $allow;
	}
}

// Setup options after import data is complete
if ( !function_exists( 'trx_addons_wpml_importer_after_import_end' ) ) {
	add_filter( 'trx_addons_action_importer_import_end', 'trx_addons_wpml_importer_after_import_end', 11, 1 );
	function trx_addons_wpml_importer_after_import_end( $importer ) {
		if ( trx_addons_exists_wpml() && in_array( 'sitepress-multilingual-cms', $importer->options['required_plugins'] ) ) {
			icl_sitepress_activate();
		}
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'trx_addons_wpml_importer_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params',	'trx_addons_wpml_importer_show_params', 10, 1 );
	function trx_addons_wpml_importer_show_params($importer) {
		if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'sitepress-multilingual-cms',
				'title' => esc_html__('Import Sitepress Multilingual CMS (WPML)', 'trx_addons'),
				'part' => 0
			));
		}
	}
}

// Prepare tables
if ( !function_exists( 'trx_addons_wpml_importer_clear_tables' ) ) {
    if (is_admin()) add_action( 'trx_addons_action_importer_clear_tables',	'trx_addons_wpml_importer_clear_tables', 10, 2 );
    function trx_addons_wpml_importer_clear_tables($importer, $clear_tables) {
        if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $importer->options['required_plugins']) ) {

        	global $wpdb;
        	if ( class_exists('WPML_ST_Upgrade_MO_Scanning') ) {
        		$wpml_st = new WPML_ST_Upgrade_MO_Scanning($wpdb);
        		$wpml_st->run();
        	}
        	if ( class_exists('WPML_Package_Translation_Schema') ) {
        		$wpml_st = new WPML_Package_Translation_Schema('');
        		$wpml_st->run_update();
        	}

        }
    }
}

// Import posts
if ( !function_exists( 'trx_addons_wpml_importer_import' ) ) {
	add_action( 'trx_addons_action_importer_import',	'trx_addons_wpml_importer_import', 10, 2 );
	function trx_addons_wpml_importer_import($importer, $action) {
		if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $importer->options['required_plugins']) ) {
			if ( $action == 'import_sitepress-multilingual-cms' ) {
				$importer->response['start_from_id'] = 0;
				$importer->import_dump('sitepress-multilingual-cms', esc_html__('Sitepress Multilingual CMS (WPML) data', 'trx_addons'));
				$importer->import_theme_options();
			}
		}
	}
}

// Display import progress
if ( !function_exists( 'trx_addons_wpml_importer_import_fields' ) ) {
	add_action( 'trx_addons_action_importer_import_fields',	'trx_addons_wpml_importer_import_fields', 10, 1 );
	function trx_addons_wpml_importer_import_fields($importer) {
		if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
				'slug'=>'sitepress-multilingual-cms', 
				'title' => esc_html__('Sitepress Multilingual CMS (WPML) data', 'trx_addons')
				)
			);
		}
	}
}

// Add table 'icl_translations' to the list of tables, whos records must be imported 'one by one'
// (not in the big combined insert statement)
if ( !function_exists( 'trx_addons_wpml_importer_import_separate_insert' ) ) {
	add_filter( 'trx_addons_filter_importer_separate_insert', 'trx_addons_wpml_importer_import_separate_insert', 10, 1 );
	function trx_addons_wpml_importer_import_separate_insert($tables) {
		if ( is_array( $tables ) ) {
			$tables[] = 'icl_translations';
		}
		return $tables;
	}
}

// Export posts
if ( !function_exists( 'trx_addons_wpml_importer_export' ) ) {
	add_action( 'trx_addons_action_importer_export',	'trx_addons_wpml_importer_export', 10, 1 );
	function trx_addons_wpml_importer_export($importer) {
		if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $importer->options['required_plugins']) ) {
			trx_addons_fpc($importer->export_file_dir('sitepress-multilingual-cms.txt'), serialize( apply_filters( 'trx_addons_filter_importer_export_tables', array(
				"icl_languages"				=> $importer->export_dump("icl_languages"),
				"icl_locale_map"			=> $importer->export_dump("icl_locale_map"),
				"icl_strings"				=> $importer->export_dump("icl_strings"),
				"icl_string_packages"		=> $importer->export_dump("icl_string_packages"),
				"icl_string_pages"			=> $importer->export_dump("icl_string_pages"),
				"icl_string_positions"		=> $importer->export_dump("icl_string_positions"),
				"icl_translate"				=> $importer->export_dump("icl_translate"),
				"icl_translate_job"			=> $importer->export_dump("icl_translate_job"),
				"icl_translations"			=> $importer->export_dump("icl_translations"),
				"icl_translation_batches"	=> $importer->export_dump("icl_translation_batches"),
				"icl_translation_status"	=> $importer->export_dump("icl_translation_status"),
				"icl_string_translations"	=> $importer->export_dump("icl_string_translations"),
				), 'wpml' ) )
			);
		}
	}
}

// Display exported data in the fields
if ( !function_exists( 'trx_addons_wpml_importer_export_fields' ) ) {
	add_action( 'trx_addons_action_importer_export_fields',	'trx_addons_wpml_importer_export_fields', 10, 1 );
	function trx_addons_wpml_importer_export_fields($importer) {
		if ( trx_addons_exists_wpml() && in_array('sitepress-multilingual-cms', $importer->options['required_plugins']) ) {
			$importer->show_exporter_fields(array(
				'slug'	=> 'sitepress-multilingual-cms',
				'title' => esc_html__('Sitepress Multilingual CMS (WPML) data', 'trx_addons')
				)
			);
		}
	}
}
