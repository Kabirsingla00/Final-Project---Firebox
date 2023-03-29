<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.10
 */


// Socials
if ( freightco_is_on(freightco_get_theme_option('socials_in_footer')) && ($freightco_output = freightco_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php freightco_show_layout($freightco_output); ?>
		</div>
	</div>
	<?php
}
?>