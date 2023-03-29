<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_args = get_query_var('freightco_logo_args');

// Site logo
$freightco_logo_type   = isset($freightco_args['type']) ? $freightco_args['type'] : '';
$freightco_logo_image  = freightco_get_logo_image($freightco_logo_type);
$freightco_logo_text   = freightco_is_on(freightco_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$freightco_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($freightco_logo_image) || !empty($freightco_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url(home_url('/')); ?>"><?php
		if (!empty($freightco_logo_image)) {
			if (empty($freightco_logo_type) && function_exists('the_custom_logo') && is_numeric( $freightco_logo_image['logo'] ) && $freightco_logo_image['logo'] > 0) {
				the_custom_logo();
			} else {
				$freightco_attr = freightco_getimagesize($freightco_logo_image);
				echo '<img src="'.esc_url($freightco_logo_image).'" alt="'.esc_attr($freightco_logo_text).'"'.(!empty($freightco_attr[3]) ? ' '.wp_kses_data($freightco_attr[3]) : '').'>';
			}
		} else {
			freightco_show_layout(freightco_prepare_macros($freightco_logo_text), '<span class="logo_text">', '</span>');
			freightco_show_layout(freightco_prepare_macros($freightco_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>