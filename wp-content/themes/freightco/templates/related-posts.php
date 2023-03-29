<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_link = get_permalink();
$freightco_post_format = get_post_format();
$freightco_post_format = empty($freightco_post_format) ? 'standard' : str_replace('post-format-', '', $freightco_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($freightco_post_format) ); ?>><?php
	freightco_show_post_featured(array(
		'thumb_size' => apply_filters('freightco_filter_related_thumb_size', freightco_get_thumb_size( (int) freightco_get_theme_option('related_posts') == 1 ? 'huge' : 'big' )),
		'show_no_image' => freightco_get_theme_setting('allow_no_image'),
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">'.wp_kses(freightco_get_post_categories(''), 'freightco_kses_content' ).'</div>'
							. '<h6 class="post_title entry-title"><a href="'.esc_url($freightco_link).'">'.esc_html(get_the_title()).'</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="'.esc_url($freightco_link).'">'.wp_kses_data(freightco_get_date()).'</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>