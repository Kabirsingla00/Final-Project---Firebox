<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

freightco_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	freightco_show_layout(get_query_var('blog_archive_start'));

	$freightco_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$freightco_sticky_out = freightco_get_theme_option('sticky_style')=='columns' 
							&& is_array($freightco_stickies) && count($freightco_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$freightco_cat = freightco_get_theme_option('parent_cat');
	$freightco_post_type = freightco_get_theme_option('post_type');
	$freightco_taxonomy = freightco_get_post_type_taxonomy($freightco_post_type);
	$freightco_show_filters = freightco_get_theme_option('show_filters');
	$freightco_tabs = array();
	if (!freightco_is_off($freightco_show_filters)) {
		$freightco_args = array(
			'type'			=> $freightco_post_type,
			'child_of'		=> $freightco_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'taxonomy'		=> $freightco_taxonomy,
			'pad_counts'	=> false
		);
		$freightco_portfolio_list = get_terms($freightco_args);
		if (is_array($freightco_portfolio_list) && count($freightco_portfolio_list) > 0) {
			$freightco_tabs[$freightco_cat] = esc_html__('All', 'freightco');
			foreach ($freightco_portfolio_list as $freightco_term) {
				if (isset($freightco_term->term_id)) $freightco_tabs[$freightco_term->term_id] = $freightco_term->name;
			}
		}
	}
	if (count($freightco_tabs) > 0) {
		$freightco_portfolio_filters_ajax = true;
		$freightco_portfolio_filters_active = $freightco_cat;
		$freightco_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters freightco_tabs freightco_tabs_ajax">
			<ul class="portfolio_titles freightco_tabs_titles">
				<?php
				foreach ($freightco_tabs as $freightco_id=>$freightco_title) {
					?><li><a href="<?php echo esc_url(freightco_get_hash_link(sprintf('#%s_%s_content', $freightco_portfolio_filters_id, $freightco_id))); ?>" data-tab="<?php echo esc_attr($freightco_id); ?>"><?php echo esc_html($freightco_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$freightco_ppp = freightco_get_theme_option('posts_per_page');
			if (freightco_is_inherit($freightco_ppp)) $freightco_ppp = '';
			foreach ($freightco_tabs as $freightco_id=>$freightco_title) {
				$freightco_portfolio_need_content = $freightco_id==$freightco_portfolio_filters_active || !$freightco_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $freightco_portfolio_filters_id, $freightco_id)); ?>"
					class="portfolio_content freightco_tabs_content"
					data-blog-template="<?php echo esc_attr(freightco_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(freightco_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($freightco_ppp); ?>"
					data-post-type="<?php echo esc_attr($freightco_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($freightco_taxonomy); ?>"
					data-cat="<?php echo esc_attr($freightco_id); ?>"
					data-parent-cat="<?php echo esc_attr($freightco_cat); ?>"
					data-need-content="<?php echo (false===$freightco_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($freightco_portfolio_need_content) 
						freightco_show_portfolio_posts(array(
							'cat' => $freightco_id,
							'parent_cat' => $freightco_cat,
							'taxonomy' => $freightco_taxonomy,
							'post_type' => $freightco_post_type,
							'page' => 1,
							'sticky' => $freightco_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		freightco_show_portfolio_posts(array(
			'cat' => $freightco_cat,
			'parent_cat' => $freightco_cat,
			'taxonomy' => $freightco_taxonomy,
			'post_type' => $freightco_post_type,
			'page' => 1,
			'sticky' => $freightco_sticky_out
			)
		);
	}

	freightco_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>