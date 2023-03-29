<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

						// Widgets area inside page content
						freightco_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					freightco_create_widgets_area('widgets_below_page');

					$freightco_body_style = freightco_get_theme_option('body_style');
					if ($freightco_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$freightco_footer_type = freightco_get_theme_option("footer_type");
			if ($freightco_footer_type == 'custom' && !freightco_is_layouts_available())
				$freightco_footer_type = 'default';
			get_template_part( "templates/footer-{$freightco_footer_type}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (false && freightco_is_on(freightco_get_theme_option('debug_mode')) && freightco_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(freightco_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>