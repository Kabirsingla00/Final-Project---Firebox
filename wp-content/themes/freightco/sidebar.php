<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

if (freightco_sidebar_present()) {
	ob_start();
	$freightco_sidebar_name = freightco_get_theme_option('sidebar_widgets');
	freightco_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($freightco_sidebar_name) ) {
		dynamic_sidebar($freightco_sidebar_name);
	}
	$freightco_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($freightco_out)) {
		$freightco_sidebar_position = freightco_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($freightco_sidebar_position); ?> widget_area<?php if (!freightco_is_inherit(freightco_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(freightco_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'freightco_action_before_sidebar' );
				freightco_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $freightco_out));
				do_action( 'freightco_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>