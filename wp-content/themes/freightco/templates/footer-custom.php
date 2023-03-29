<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.10
 */

$freightco_footer_id = str_replace('footer-custom-', '', freightco_get_theme_option("footer_style"));
if ((int) $freightco_footer_id == 0) {
	$freightco_footer_id = freightco_get_post_id(array(
												'name' => $freightco_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$freightco_footer_id = apply_filters('freightco_filter_get_translated_layout', $freightco_footer_id);
}
$freightco_footer_meta = get_post_meta($freightco_footer_id, 'trx_addons_options', true);
if (!empty($freightco_footer_meta['margin']) != '') 
	freightco_add_inline_css(sprintf('.page_content_wrap{padding-bottom:%s}', esc_attr(freightco_prepare_css_value($freightco_footer_meta['margin']))));
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($freightco_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($freightco_footer_id))); 
						if (!freightco_is_inherit(freightco_get_theme_option('footer_scheme')))
							echo ' scheme_' . esc_attr(freightco_get_theme_option('footer_scheme'));
						?>">
	<?php
    // Custom footer's layout
    do_action('freightco_action_show_layout', $freightco_footer_id);
	?>
</footer><!-- /.footer_wrap -->
