<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.1
 */
 
$freightco_theme_obj = wp_get_theme();

?>
<div class="freightco_admin_notice freightco_rate_notice update-nag"><?php
	// Theme image
	if ( ($freightco_theme_img = freightco_get_file_url('screenshot.jpg')) != '') {
		?><div class="freightco_notice_image"><img src="<?php echo esc_url($freightco_theme_img); ?>" alt="<?php esc_attr__('screenshot', 'freightco'); ?>"></div><?php
	}

	// Title
	?><h3 class="freightco_notice_title"><a href="<?php echo esc_url(freightco_storage_get('theme_download_url')); ?>" target="_blank"><?php
		// Translators: Add theme name and version to the 'Welcome' message
		echo esc_html(sprintf(esc_html__('Rate our theme "%s", please', 'freightco'),
				$freightco_theme_obj->name . (FREIGHTCO_THEME_FREE ? ' ' . esc_html__('Free', 'freightco') : '')
				));
	?></a></h3><?php
	
	// Description
	?><div class="freightco_notice_text">
		<p><?php echo wp_kses_data(__('We are glad you chose our WP theme for your website. You’ve done well customizing your website and we hope that you’ve enjoyed working with our theme.', 'freightco')); ?></p>
		<p><?php echo wp_kses_data(__('It would be just awesome if you spend just a minute of your time to rate our theme or the customer service you’ve received from us.', 'freightco')); ?></p>
		<p class="freightco_notice_text_info"><?php echo wp_kses_data(__('* We love receiving your reviews! Every time you leave a review, our CEO Henry Rise gives $5 to homeless dog shelter! Save the planet with us!', 'freightco')); ?></p>
	</div><?php

	// Buttons
	?><div class="freightco_notice_buttons"><?php
		// Link to the theme download page
		?><a href="<?php echo esc_url(freightco_storage_get('theme_download_url')); ?>" class="button button-primary" target="_blank"><i class="dashicons dashicons-star-filled"></i> <?php
			// Translators: Add theme name
			echo esc_html(sprintf(__('Rate theme %s', 'freightco'), $freightco_theme_obj->name));
		?></a><?php
		// Link to the theme support
		?><a href="<?php echo esc_url(freightco_storage_get('theme_support_url')); ?>" class="button" target="_blank"><i class="dashicons dashicons-sos"></i> <?php
			esc_html_e('Support', 'freightco');
		?></a><?php
		// Link to the theme documentation
		?><a href="<?php echo esc_url(freightco_storage_get('theme_doc_url')); ?>" class="button" target="_blank"><i class="dashicons dashicons-book"></i> <?php
			esc_html_e('Documentation', 'freightco');
		?></a><?php
		// Dismiss
		?><a href="#" class="freightco_hide_notice"><i class="dashicons dashicons-dismiss"></i> <span class="freightco_hide_notice_text"><?php esc_html_e('Dismiss', 'freightco'); ?></span></a>
	</div>
</div>