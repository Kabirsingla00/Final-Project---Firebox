<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.10
 */

// Footer sidebar
$freightco_footer_name = freightco_get_theme_option('footer_widgets');
$freightco_footer_present = !freightco_is_off($freightco_footer_name) && is_active_sidebar($freightco_footer_name);
if ($freightco_footer_present) { 
	freightco_storage_set('current_sidebar', 'footer');
	$freightco_footer_wide = freightco_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($freightco_footer_name) ) {
		dynamic_sidebar($freightco_footer_name);
	}
	$freightco_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($freightco_out)) {
		$freightco_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $freightco_out);
		$freightco_need_columns = true;	//or check: strpos($freightco_out, 'columns_wrap')===false;
		if ($freightco_need_columns) {
			$freightco_columns = max(0, (int) freightco_get_theme_option('footer_columns'));
			if ($freightco_columns == 0) $freightco_columns = min(4, max(1, substr_count($freightco_out, '<aside ')));
			if ($freightco_columns > 1)
				$freightco_out = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($freightco_columns).' widget', $freightco_out);
			else
				$freightco_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($freightco_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$freightco_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($freightco_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'freightco_action_before_sidebar' );
				freightco_show_layout($freightco_out);
				do_action( 'freightco_action_after_sidebar' );
				if ($freightco_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$freightco_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>