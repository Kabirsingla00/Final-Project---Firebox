<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.06
 */

$freightco_header_css = '';
$freightco_header_image = get_header_image();
$freightco_header_video = freightco_get_header_video();
if (!empty($freightco_header_image) && freightco_trx_addons_featured_image_override(is_singular() || freightco_storage_isset('blog_archive') || is_category())) {
	$freightco_header_image = freightco_get_current_mode_image($freightco_header_image);
}

$freightco_header_id = str_replace('header-custom-', '', freightco_get_theme_option("header_style"));
if ((int) $freightco_header_id == 0) {
	$freightco_header_id = freightco_get_post_id(array(
												'name' => $freightco_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$freightco_header_id = apply_filters('freightco_filter_get_translated_layout', $freightco_header_id);
}
$freightco_header_meta = get_post_meta($freightco_header_id, 'trx_addons_options', true);
if (!empty($freightco_header_meta['margin']) != '') 
	freightco_add_inline_css(sprintf('.page_content_wrap{padding-top:%s}', esc_attr(freightco_prepare_css_value($freightco_header_meta['margin']))));

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($freightco_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($freightco_header_id)));
				echo !empty($freightco_header_image) || !empty($freightco_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($freightco_header_video!='') 
					echo ' with_bg_video';
				if ($freightco_header_image!='') 
					echo ' '.esc_attr(freightco_add_inline_css_class('background-image: url('.esc_url($freightco_header_image).');'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (freightco_is_on(freightco_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight freightco-full-height';
				if (!freightco_is_inherit(freightco_get_theme_option('header_scheme')))
					echo ' scheme_' . esc_attr(freightco_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($freightco_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('freightco_action_show_layout', $freightco_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>