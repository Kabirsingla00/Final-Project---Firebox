<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('freightco_wpml_get_css')) {
	add_filter('freightco_filter_get_css', 'freightco_wpml_get_css', 10, 2);
	function freightco_wpml_get_css($css, $args) {
		return $css;
	}
}
?>