<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_header_css = '';
$freightco_header_image = get_header_image();
$freightco_header_video = freightco_get_header_video();
if (!empty($freightco_header_image) && freightco_trx_addons_featured_image_override(is_singular() || freightco_storage_isset('blog_archive') || is_category())) {
	$freightco_header_image = freightco_get_current_mode_image($freightco_header_image);
}

?><header class="top_panel top_panel_default<?php
					echo !empty($freightco_header_image) || !empty($freightco_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($freightco_header_video!='') echo ' with_bg_video';
					if ($freightco_header_image!='') echo ' '.esc_attr(freightco_add_inline_css_class('background-image: url('.esc_url($freightco_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (freightco_is_on(freightco_get_theme_option('header_fullheight'))) echo ' header_fullheight freightco-full-height';
					if (!freightco_is_inherit(freightco_get_theme_option('header_scheme')))
						echo ' scheme_' . esc_attr(freightco_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($freightco_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (freightco_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Mobile header
	if (freightco_is_on(freightco_get_theme_option("header_mobile_enabled"))) {
		get_template_part( 'templates/header-mobile' );
	}
	
	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Display featured image in the header on the single posts
	// Comment next line to prevent show featured image in the header area
	// and display it in the post's content
	if (false) { get_template_part( 'templates/header-single' ); }

?></header>