<?php
/**
 * The template to display single post
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

get_header();

while ( have_posts() ) { the_post();

	get_template_part( 'content', get_post_format() );

	// Previous/next post navigation.
	?><div class="nav-links-single"><?php
		the_post_navigation( array(
			'next_text' => '<span class="nav-arrow"></span>'
				. '<span class="screen-reader-text">' . esc_html__( 'Next post:', 'freightco' ) . '</span> '
				. '<div class="post-next">'. esc_html__('next post', 'freightco') .'</div>'
				. '<h6 class="post-title">%title</h6>',
			'prev_text' => '<span class="nav-arrow"></span>'
				. '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'freightco' ) . '</span> '
				. '<div class="post-prev">'. esc_html__('prev post', 'freightco') .'</div>'
				. '<h6 class="post-title">%title</h6>'
		) );
	?></div><?php

	// Related posts
	if ((int) freightco_get_theme_option('show_related_posts') && ($freightco_related_posts = (int) freightco_get_theme_option('related_posts')) > 0) {
		freightco_show_related_posts(array('orderby' => 'rand',
										'posts_per_page' => max(1, min(9, $freightco_related_posts)),
										'columns' => max(1, min(4, freightco_get_theme_option('related_columns')))
										),
									freightco_get_theme_option('related_style')
									);
	}

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

get_footer();
?>