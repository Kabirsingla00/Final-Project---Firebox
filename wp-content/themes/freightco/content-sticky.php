<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$freightco_post_format = get_post_format();
$freightco_post_format = empty($freightco_post_format) ? 'standard' : str_replace('post-format-', '', $freightco_post_format);
$freightco_animation = freightco_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($freightco_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($freightco_post_format) ); ?>
	<?php echo (!freightco_is_off($freightco_animation) ? ' data-animation="'.esc_attr(freightco_get_animation_classes($freightco_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	freightco_show_post_featured(array(
		'thumb_size' => freightco_get_thumb_size($freightco_columns==1 ? 'big' : ($freightco_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($freightco_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			freightco_show_post_meta(apply_filters('freightco_filter_post_meta_args', array(), 'sticky', $freightco_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>