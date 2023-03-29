<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

// Page (category, tag, archive, author) title

if ( freightco_need_page_title() ) {
	freightco_sc_layouts_showed('title', true);
	freightco_sc_layouts_showed('postmeta', false);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php

						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$freightco_blog_title = freightco_get_blog_title();
							$freightco_blog_title_text = $freightco_blog_title_class = $freightco_blog_title_link = $freightco_blog_title_link_text = '';
							if (is_array($freightco_blog_title)) {
								$freightco_blog_title_text = $freightco_blog_title['text'];
								$freightco_blog_title_class = !empty($freightco_blog_title['class']) ? ' '.$freightco_blog_title['class'] : '';
								$freightco_blog_title_link = !empty($freightco_blog_title['link']) ? $freightco_blog_title['link'] : '';
								$freightco_blog_title_link_text = !empty($freightco_blog_title['link_text']) ? $freightco_blog_title['link_text'] : '';
							} else
								$freightco_blog_title_text = $freightco_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($freightco_blog_title_class); ?>"><?php
								$freightco_top_icon = freightco_get_category_icon();
								if (!empty($freightco_top_icon)) {
									$freightco_attr = freightco_getimagesize($freightco_top_icon);
									?><img src="<?php echo esc_url($freightco_top_icon); ?>" alt="<?php esc_attr__('image', 'freightco'); ?>" <?php if (!empty($freightco_attr[3])) freightco_show_layout($freightco_attr[3]);?>><?php
								}
								echo wp_kses_post($freightco_blog_title_text);
							?></h1>
							<?php
							if (!empty($freightco_blog_title_link) && !empty($freightco_blog_title_link_text)) {
								?><a href="<?php echo esc_url($freightco_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($freightco_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'freightco_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>