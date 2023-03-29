<div class="front_page_section front_page_section_team<?php
			$freightco_scheme = freightco_get_theme_option('front_page_team_scheme');
			if (!freightco_is_inherit($freightco_scheme)) echo ' scheme_'.esc_attr($freightco_scheme);
			echo ' front_page_section_paddings_'.esc_attr(freightco_get_theme_option('front_page_team_paddings'));
		?>"<?php
		$freightco_css = '';
		$freightco_bg_image = freightco_get_theme_option('front_page_team_bg_image');
		if (!empty($freightco_bg_image)) 
			$freightco_css .= 'background-image: url('.esc_url(freightco_get_attachment_url($freightco_bg_image)).');';
		if (!empty($freightco_css))
			echo ' style="' . esc_attr($freightco_css) . '"';
?>><?php
	// Add anchor
	$freightco_anchor_icon = freightco_get_theme_option('front_page_team_anchor_icon');	
	$freightco_anchor_text = freightco_get_theme_option('front_page_team_anchor_text');	
	if ((!empty($freightco_anchor_icon) || !empty($freightco_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_team"'
										. (!empty($freightco_anchor_icon) ? ' icon="'.esc_attr($freightco_anchor_icon).'"' : '')
										. (!empty($freightco_anchor_text) ? ' title="'.esc_attr($freightco_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_team_inner<?php
			if (freightco_get_theme_option('front_page_team_fullheight'))
				echo ' freightco-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$freightco_css = '';
			$freightco_bg_mask = freightco_get_theme_option('front_page_team_bg_mask');
			$freightco_bg_color = freightco_get_theme_option('front_page_team_bg_color');
			if (!empty($freightco_bg_color) && $freightco_bg_mask > 0)
				$freightco_css .= 'background-color: '.esc_attr($freightco_bg_mask==1
																	? $freightco_bg_color
																	: freightco_hex2rgba($freightco_bg_color, $freightco_bg_mask)
																).';';
			if (!empty($freightco_css))
				echo ' style="' . esc_attr($freightco_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_team_content_wrap content_wrap">
			<?php
			// Caption
			$freightco_caption = freightco_get_theme_option('front_page_team_caption');
			if (!empty($freightco_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_team_caption front_page_block_<?php echo !empty($freightco_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses($freightco_caption, 'freightco_kses_content'); ?></h2><?php
			}
		
			// Description (text)
			$freightco_description = freightco_get_theme_option('front_page_team_description');
			if (!empty($freightco_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_team_description front_page_block_<?php echo !empty($freightco_description) ? 'filled' : 'empty'; ?>"><?php echo wp_kses(wpautop($freightco_description), 'freightco_kses_content' ); ?></div><?php
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_team_output"><?php 
				if (is_active_sidebar('front_page_team_widgets')) {
					dynamic_sidebar( 'front_page_team_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!freightco_exists_trx_addons())
						freightco_customizer_need_trx_addons_message();
					else
						freightco_customizer_need_widgets_message('front_page_team_caption', 'ThemeREX Addons - Team');
				}
			?></div>
		</div>
	</div>
</div>