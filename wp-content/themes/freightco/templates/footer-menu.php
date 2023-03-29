<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.10
 */

// Footer menu
$freightco_menu_footer = freightco_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($freightco_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php freightco_show_layout($freightco_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>