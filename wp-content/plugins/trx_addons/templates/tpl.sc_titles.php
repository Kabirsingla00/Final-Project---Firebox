<?php
/**
 * The template to display shortcode's title, subtitle and description
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.08
 */

extract(get_query_var('trx_addons_args_sc_show_titles'));

$align = !empty($args['title_align']) ? ' sc_align_'.trim($args['title_align']) : '';
$style = !empty($args['title_style']) ? ' sc_item_title_style_'.trim($args['title_style']) : '';
$style_title = !empty($args['title_style']) ? ' title_style_'.trim($args['title_style']) : '';
$alter_border = !empty($args['border_alter']) ? ' border_alter_color' : '';

?>

<?php
if (trx_addons_is_off($args['title_tag'])) {
	if (!empty($args['subtitle']) || !empty($args['title'])) {
?>
<div class="sc_title_container <?php echo esc_attr($align . $style_title . $alter_border) ?>">
<?php
	}
}
?>
<?php
if (!empty($args['subtitle'])) {
	?><h6 class="<?php 
			echo esc_attr(apply_filters('trx_addons_filter_sc_item_subtitle_class', 'sc_item_subtitle '.$sc.'_subtitle'.$align.$style, $sc));
			?>"><?php 
			trx_addons_show_layout(trx_addons_prepare_macros($args['subtitle']));
	?></h6><?php
}
if (!empty($args['title'])) {
	if (empty($size)) $size = 'large';	//is_page() ? 'large' : 'normal';
	$title_tag = !empty($args['title_tag']) && !trx_addons_is_off($args['title_tag'])
					? $args['title_tag']
					: apply_filters('trx_addons_filter_sc_item_title_tag', 'large' == $size ? 'h2' : ('tiny' == $size ? 'h4' : 'h3'));
	$title_tag_class = !empty($args['title_tag']) && !trx_addons_is_off($args['title_tag'])
							? ' sc_item_title_tag'
							: '';
	?><<?php echo esc_attr($title_tag); ?> class="<?php 
		echo esc_attr(apply_filters('trx_addons_filter_sc_item_title_class', 'sc_item_title '.$sc.'_title'.$align.$style.$title_tag_class, $sc));
		?>"><?php
		trx_addons_show_layout(trx_addons_prepare_macros($args['title']));
	?></<?php echo esc_attr($title_tag); ?>><?php
}
?>
<?php
if (trx_addons_is_off($args['title_tag'])) {
	if (!empty($args['subtitle']) || !empty($args['title'])) {
?>
</div>
<?php
	}
}
?>

<?php
if (!empty($args['description'])) {
	?><div class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_description_class', 'sc_item_descr '.$sc.'_descr'.$align, $sc)); ?>"><?php trx_addons_show_layout(wpautop(do_shortcode(trx_addons_prepare_macros($args['description'])))); ?></div><?php
}