<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WordPress editor or any Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$freightco_content = '';
$freightco_blog_archive_mask = '%%CONTENT%%';
$freightco_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $freightco_blog_archive_mask);
if ( have_posts() ) {
	the_post();
	if (($freightco_content = apply_filters('the_content', get_the_content())) != '') {
		if (($freightco_pos = strpos($freightco_content, $freightco_blog_archive_mask)) !== false) {
			$freightco_content = preg_replace('/(\<p\>\s*)?'.$freightco_blog_archive_mask.'(\s*\<\/p\>)/i', $freightco_blog_archive_subst, $freightco_content);
		} else
			$freightco_content .= $freightco_blog_archive_subst;
		$freightco_content = explode($freightco_blog_archive_mask, $freightco_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) freightco_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$freightco_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$freightco_args = freightco_query_add_posts_and_cats($freightco_args, '', freightco_get_theme_option('post_type'), freightco_get_theme_option('parent_cat'));
$freightco_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($freightco_page_number > 1) {
	$freightco_args['paged'] = $freightco_page_number;
	$freightco_args['ignore_sticky_posts'] = true;
}
$freightco_ppp = freightco_get_theme_option('posts_per_page');
if ((int) $freightco_ppp != 0)
	$freightco_args['posts_per_page'] = (int) $freightco_ppp;
// Make a new main query
$GLOBALS['wp_the_query']->query($freightco_args);


// Add internal query vars in the new query!
if (is_array($freightco_content) && count($freightco_content) == 2) {
	set_query_var('blog_archive_start', $freightco_content[0]);
	set_query_var('blog_archive_end', $freightco_content[1]);
}

get_template_part('index');
?>