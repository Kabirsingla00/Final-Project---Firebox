<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_blog_style = explode('_', freightco_get_theme_option('blog_style'));
$freightco_columns = empty($freightco_blog_style[1]) ? 2 : max(2, $freightco_blog_style[1]);
$freightco_expanded = !freightco_sidebar_present() && freightco_is_on(freightco_get_theme_option('expand_content'));
$freightco_post_format = get_post_format();
$freightco_post_format = empty($freightco_post_format) ? 'standard' : str_replace('post-format-', '', $freightco_post_format);
$freightco_animation = freightco_get_theme_option('blog_animation');
$freightco_components = freightco_array_get_keys_by_value(freightco_get_theme_option('meta_parts'));
$freightco_counters = freightco_array_get_keys_by_value(freightco_get_theme_option('counters'));

?><div class="<?php echo 'classic' == $freightco_blog_style[0] ? 'column' : 'masonry_item masonry_item'; ?>-1_<?php echo esc_attr($freightco_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_format_'.esc_attr($freightco_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($freightco_columns)
					. ' post_layout_'.esc_attr($freightco_blog_style[0]) 
					. ' post_layout_'.esc_attr($freightco_blog_style[0]).'_'.esc_attr($freightco_columns)
					); ?>
	<?php echo (!freightco_is_off($freightco_animation) ? ' data-animation="'.esc_attr(freightco_get_animation_classes($freightco_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	freightco_show_post_featured( array( 'thumb_size' => freightco_get_thumb_size($freightco_blog_style[0] == 'classic'
													? (strpos(freightco_get_theme_option('body_style'), 'full')!==false 
															? ( $freightco_columns > 2 ? 'big' : 'huge' )
															: (	$freightco_columns > 2
																? ($freightco_expanded ? 'med' : 'small')
																: ($freightco_expanded ? 'big' : 'med')
																)
														)
													: (strpos(freightco_get_theme_option('body_style'), 'full')!==false 
															? ( $freightco_columns > 2 ? 'masonry-big' : 'full' )
															: (	$freightco_columns <= 2 && $freightco_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	
	?>

	<div class="post_content entry-content">
		<?php
		if ( !in_array($freightco_post_format, array('link', 'aside', 'status', 'quote')) ) {
			?>
			<div class="post_header entry-header">
				<?php 
				do_action('freightco_action_before_post_title'); 

				// Post title
				the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				?>
			</div><!-- .entry-header -->
			<?php
		}	
		?>
		<div class="post_content_inner">
			<?php
			$freightco_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($freightco_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($freightco_post_format == 'quote') {
				if (($quote = freightco_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					freightco_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 4)!='[vc_') {
				echo wp_trim_words( get_the_content(), 15, '...' );
			}
			?>
		</div>
		<div class="post_meta_container">
			<?php
			do_action('freightco_action_before_post_meta'); 

			// Post meta
			$freightco_components = freightco_array_get_keys_by_value(freightco_get_theme_option('meta_parts'));
			$freightco_counters = freightco_array_get_keys_by_value(freightco_get_theme_option('counters'));

			if (!empty($freightco_components))
				freightco_show_post_meta(apply_filters('freightco_filter_post_meta_args', array(
					'components' => $freightco_components,
					'counters' => $freightco_counters,
					'seo' => false
					), 'excerpt', 1)
				);
			?>
		</div>		
		<?php
		// More button
		if ( $freightco_show_learn_more ) {
			?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'freightco'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>