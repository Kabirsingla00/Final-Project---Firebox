<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

// Header sidebar
$freightco_header_name = freightco_get_theme_option('header_widgets');
$freightco_header_present = !freightco_is_off($freightco_header_name) && is_active_sidebar($freightco_header_name);
if ($freightco_header_present) { 
	freightco_storage_set('current_sidebar', 'header');
	$freightco_header_wide = freightco_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($freightco_header_name) ) {
		dynamic_sidebar($freightco_header_name);
	}
	$freightco_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($freightco_widgets_output)) {
		$freightco_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $freightco_widgets_output);
		$freightco_need_columns = strpos($freightco_widgets_output, 'columns_wrap')===false;
		if ($freightco_need_columns) {
			$freightco_columns = max(0, (int) freightco_get_theme_option('header_columns'));
			if ($freightco_columns == 0) $freightco_columns = min(6, max(1, substr_count($freightco_widgets_output, '<aside ')));
			if ($freightco_columns > 1)
				$freightco_widgets_output = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($freightco_columns).' widget', $freightco_widgets_output);
			else
				$freightco_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($freightco_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$freightco_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($freightco_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'freightco_action_before_sidebar' );
				freightco_show_layout($freightco_widgets_output);
				do_action( 'freightco_action_after_sidebar' );
				if ($freightco_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$freightco_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>