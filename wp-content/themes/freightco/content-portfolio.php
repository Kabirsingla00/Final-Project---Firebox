<?php
/**
 * The Portfolio template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_blog_style = explode('_', freightco_get_theme_option('blog_style'));
$freightco_columns = empty($freightco_blog_style[1]) ? 2 : max(2, $freightco_blog_style[1]);
$freightco_post_format = get_post_format();
$freightco_post_format = empty($freightco_post_format) ? 'standard' : str_replace('post-format-', '', $freightco_post_format);
$freightco_animation = freightco_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($freightco_columns).' post_format_'.esc_attr($freightco_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!freightco_is_off($freightco_animation) ? ' data-animation="'.esc_attr(freightco_get_animation_classes($freightco_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$freightco_image_hover = freightco_get_theme_option('image_hover');
	// Featured image
	freightco_show_post_featured(array(
		'thumb_size' => freightco_get_thumb_size(strpos(freightco_get_theme_option('body_style'), 'full')!==false || $freightco_columns < 3 
								? 'masonry-big' 
								: 'masonry'),
		'show_no_image' => true,
		'class' => $freightco_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $freightco_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>