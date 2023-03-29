<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.10
 */

// Logo
if (freightco_is_on(freightco_get_theme_option('logo_in_footer'))) {
	$freightco_logo_image = freightco_get_logo_image('footer');
	$freightco_logo_text  = get_bloginfo( 'name' );
	if (!empty($freightco_logo_image) || !empty($freightco_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($freightco_logo_image)) {
					$freightco_attr = freightco_getimagesize($freightco_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($freightco_logo_image).'" class="logo_footer_image" alt="'. esc_attr__('logo', 'freightco') .'"'.(!empty($freightco_attr[3]) ? ' ' . wp_kses_data($freightco_attr[3]) : '').'></a>' ;
				} else if (!empty($freightco_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($freightco_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>