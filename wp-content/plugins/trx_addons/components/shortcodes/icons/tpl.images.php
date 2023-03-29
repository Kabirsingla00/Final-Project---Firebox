<?php
/**
 * The style "default" of the Icons
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_icons');


$icon_present = '';
$svg_present = false;

?><div id="<?php echo esc_attr($args['id']); ?>" 
	class="sc_icons sc_icons_<?php
		echo esc_attr($args['type']);
		echo ' sc_icons_size_' . esc_attr($args['size']);
		if (!empty($args['align'])) echo ' sc_align_'.esc_attr($args['align']);
		if (!empty($args['class'])) echo ' '.esc_attr($args['class']);
	?>"<?php
	if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"';
?>><div class="sc_icons_container"><?php

	trx_addons_sc_show_titles('sc_icons', $args);

	if ((int)$args['columns'] > 1) {
		?><div class="sc_icons_columns_<?php echo esc_attr($args['columns']); ?>"><?php
	}
	
	foreach ($args['icons'] as $item) {
		$item['color'] = !empty($item['color']) ? $item['color'] : $args['color'];
		?><div class="sc_icons_content"><div class="sc_icons_item<?php echo !empty($item['link']) ? ' sc_icons_item_linked' : ''; ?>"><?php
			if (!empty($item['image'])) {
				$item['image'] = trx_addons_get_attachment_url($item['image'], apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size('full'), 'icons-default'));
				if (!empty($item['image'])) {
					$attr = trx_addons_getimagesize($item['image']);
					?><div class="sc_icons_image <?php if (!empty($item['image_hover']['url'])) { echo ' with_hover'; } ?>">
						<img src="<?php echo esc_url($item['image']); ?>" alt=""<?php echo (!empty($attr[3]) ? ' '.trim($attr[3]) : ''); ?>>
						<?php if (!empty($item['image_hover']['url'])) { ?><img src="<?php echo esc_url($item['image_hover']['url']); ?>" class="sc_icons_image_hover" alt=""<?php //echo (!empty($attr[3]) ? ' '.trim($attr[3]) : ''); ?>><?php } ?>
					</div><?php
				}
			} else {
				if (empty($item['icon_type'])) $item['icon_type'] = '';
				$icon = !empty($item['icon_type']) && !empty($item['icon_' . $item['icon_type']]) && $item['icon_' . $item['icon_type']] != 'empty' 
							? $item['icon_' . $item['icon_type']] 
							: (!empty($item['icon']) && strtolower($item['icon'])!='none' ? $item['icon'] : '');
				if (!empty($icon)) {
					$svg = $img = '';
					if (trx_addons_is_url($icon)) {
						$img = $icon;
						$icon = basename($icon);
						$item['icon_type'] = 'image';
					} else if ((int)$args['icons_animation'] > 0 && ($svg = trx_addons_get_file_dir('css/icons.svg/'.trx_addons_esc($icon).'.svg')) != '') {
						$item['icon_type'] = 'svg';
						$svg_present = true;
					} else if (!empty($item['icon_type']) && strpos($icon_present, $item['icon_type'])===false) {
						$icon_present .= (!empty($icon_present) ? ',' : '') . $item['icon_type'];
					}
					?><div id="<?php echo esc_attr($args['id'].'_'.trim($icon)); ?>" class="sc_icons_icon sc_icon_type_<?php echo esc_attr($item['icon_type']); ?><?php echo empty($svg) && empty($img) ? ' '.esc_attr($icon) : ''; ?>"
						<?php if (!empty($item['color'])) echo ' style="color: '.esc_attr($item['color']).'"'; ?>
						></div><?php
				 }
			}
			if (!empty($item['title']) || !empty($item['description'])) {
				?>
				<div  class="sc_icons_item_content">
				<?php
			}
				if (!empty($item['title'])) {
					$item['title'] = explode('|', $item['title']);
					?><h4 class="sc_icons_item_title"><?php
						foreach ($item['title'] as $str) {
							?><span><?php echo esc_html($str); ?></span><?php
						}
					?></h4><?php
				}
				if (!empty($item['description'])) {
					?><div class="sc_icons_item_description"><?php
						if (strpos($item['description'], '<p>') === false) {
							$item['description'] = explode('|', str_replace("\n", '|', $item['description']));
							foreach ($item['description'] as $str) {
								?><span><?php trx_addons_show_layout($str); ?></span><?php
							}
						} else
							trx_addons_show_layout($item['description']);
					?></div><?php
				}
			if (!empty($item['title']) || !empty($item['description'])) {
				?>
				</div>
				<?php
			}
			if (!empty($item['link'])) {
				?><a href="<?php echo esc_url($item['link']); ?>" class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_link_classes', 'sc_icons_item_link', 'sc_icons', $args)); ?>"<?php
						if (!empty($item['new_window']) || !empty($item['link_extra']['is_external'])) echo ' target="_blank"';
					?>></a><?php
			}
		?></div></div><?php
	}

	if ((int)$args['columns'] > 1) {
		?></div><?php
	}

	trx_addons_sc_show_links('sc_icons', $args);

?></div></div><!-- /.sc_icons --><?php

trx_addons_load_icons($icon_present);
if (trx_addons_is_on(trx_addons_get_option('debug_mode')) && $svg_present) {
	wp_enqueue_script( 'vivus', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'icons/vivus.js'), array('jquery'), null, true );
	wp_enqueue_script( 'trx_addons-sc_icons', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'icons/icons.js'), array('jquery'), null, true );
}
?>