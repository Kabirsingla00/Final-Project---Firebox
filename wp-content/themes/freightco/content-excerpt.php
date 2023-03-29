<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

$freightco_post_format = get_post_format();
$freightco_post_format = empty($freightco_post_format) ? 'standard' : str_replace('post-format-', '', $freightco_post_format);
$freightco_animation = freightco_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($freightco_post_format) ); ?>
	<?php echo (!freightco_is_off($freightco_animation) ? ' data-animation="'.esc_attr(freightco_get_animation_classes($freightco_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	freightco_show_post_featured(array( 'thumb_size' => freightco_get_thumb_size( strpos(freightco_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	
	// Post content
	?><div class="post_content entry-content"><?php
		// Title
		if (get_the_title() != '') {
			?>
			<div class="post_header entry-header">
				<?php
				do_action('freightco_action_before_post_title'); 

				// Post title
				the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				?>
			</div><!-- .post_header --><?php
		}
		if (freightco_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'freightco' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'freightco' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$freightco_show_learn_more = !in_array($freightco_post_format, array('link', 'aside', 'status', 'audio', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
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
					the_excerpt();
				}
			?></div>

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
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><span><?php esc_html_e('Read more', 'freightco'); ?></span></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</article>