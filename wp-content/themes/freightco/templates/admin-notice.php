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
<div class="freightco_admin_notice freightco_welcome_notice update-nag"><?php
	// Theme image
	if ( ($freightco_theme_img = freightco_get_file_url('screenshot.jpg')) != '') {
		?><div class="freightco_notice_image"><img src="<?php echo esc_url($freightco_theme_img); ?>" alt="<?php esc_attr__('screenshot', 'freightco'); ?>"></div><?php
	}

	// Title
	?><h3 class="freightco_notice_title"><?php
		// Translators: Add theme name and version to the 'Welcome' message
		echo esc_html(sprintf(esc_html__('Welcome to %1$s v.%2$s', 'freightco'),
				$freightco_theme_obj->name . (FREIGHTCO_THEME_FREE ? ' ' . esc_html__('Free', 'freightco') : ''),
				$freightco_theme_obj->version
				));
	?></h3><?php

	// Description
	?><div class="freightco_notice_text"><?php
		echo str_replace('. ', '.<br>', wp_kses_data($freightco_theme_obj->description));
		if (!freightco_exists_trx_addons()) {
			echo (!empty($freightco_theme_obj->description) ? '<br><br>' : '')
					. wp_kses_data(esc_html__('Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'freightco'));
		}
	?></div><?php

	// Buttons
	?><div class="freightco_notice_buttons"><?php
		// Link to the page 'About Theme'
		?><a href="<?php echo esc_url(admin_url().'themes.php?page=freightco_about'); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> <?php
			// Translators: Add theme name
			echo esc_html(sprintf(esc_html__('About %s', 'freightco'), $freightco_theme_obj->name));
		?></a><?php
		// Link to the page 'Install plugins'
		if (freightco_get_value_gp('page')!='tgmpa-install-plugins') {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-plugins"></i> <?php esc_html_e('Install plugins', 'freightco'); ?></a>
			<?php
		}
		// Link to the 'One-click demo import'
		if (function_exists('freightco_exists_trx_addons') && freightco_exists_trx_addons() && class_exists('trx_addons_demo_data_importer')) {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=trx_importer'); ?>" class="button button-primary"><i class="dashicons dashicons-download"></i> <?php esc_html_e('One Click Demo Data', 'freightco'); ?></a>
			<?php
		}
		// Link to the Customizer
		?><a href="<?php echo esc_url(admin_url().'customize.php'); ?>" class="button"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Customizer', 'freightco'); ?></a><?php
		// Link to the Theme Options
		if (!FREIGHTCO_THEME_FREE) {
			?><span> <?php esc_html_e('or', 'freightco'); ?> </span>
        	<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>" class="button"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Options', 'freightco'); ?></a><?php
        }
        // Dismiss this notice
        ?><a href="#" class="freightco_hide_notice"><i class="dashicons dashicons-dismiss"></i> <span class="freightco_hide_notice_text"><?php esc_html_e('Dismiss', 'freightco'); ?></span></a>
	</div>
</div>