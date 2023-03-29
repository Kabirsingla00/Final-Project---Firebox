<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_link = get_permalink();
$freightco_post_format = get_post_format();
$freightco_post_format = empty($freightco_post_format) ? 'standard' : str_replace('post-format-', '', $freightco_post_format);

$image = '';
if ( has_post_thumbnail() ) {
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), freightco_get_thumb_size('relimg') );
}

if (!empty($image)) {
	$image_bg = esc_attr(freightco_add_inline_css_class('background-image:url('.esc_url($image[0]).');'));	
} else{
	$image_bg = "";
}

?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_2 post_format_'.esc_attr($freightco_post_format).' '.$image_bg.' ' ); ?>><?php
	?><div class="post_header entry-header">
		<?php if (get_the_title() != '') {?>
		<h6 class="post_title entry-title"><a href="<?php echo esc_url($freightco_link); ?>"><?php the_title(); ?></a></h6>
		<?php
		}
		freightco_show_post_meta(apply_filters('freightco_filter_post_meta_args', array(
			'components' => 'categories,counters',
			'counters' => 'comments,likes'
			))
		);
		?>
		<a class="related-more-link" href="<?php the_permalink(); ?>"><span><?php esc_html_e('Read more', 'freightco'); ?></span></a>
	</div>
</div>