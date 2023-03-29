<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

freightco_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	freightco_show_layout(get_query_var('blog_archive_start'));

	$freightco_classes = 'posts_container '
						. (substr(freightco_get_theme_option('blog_style'), 0, 7) == 'classic' ? 'columns_wrap columns_padding_bottom' : 'masonry_wrap');
	$freightco_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$freightco_sticky_out = freightco_get_theme_option('sticky_style')=='columns' 
							&& is_array($freightco_stickies) && count($freightco_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($freightco_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$freightco_sticky_out) {
		if (freightco_get_theme_option('first_post_large') && !is_paged() && !in_array(freightco_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( 'content', 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($freightco_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($freightco_sticky_out && !is_sticky()) {
			$freightco_sticky_out = false;
			?></div><div class="<?php echo esc_attr($freightco_classes); ?>"><?php
		}
		get_template_part( 'content', $freightco_sticky_out && is_sticky() ? 'sticky' : 'classic' );
	}
	
	?></div><?php

	freightco_show_pagination();

	freightco_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>