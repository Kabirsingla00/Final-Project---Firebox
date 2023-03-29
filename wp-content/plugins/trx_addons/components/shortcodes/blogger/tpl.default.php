<?php
/**
 * The style "default" of the Blogger
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_blogger');

$query_args = array(
	'post_status' => 'publish',
	'ignore_sticky_posts' => true
);
if ( empty( $args['ids'] ) || count( explode( ',', $args['ids'] ) ) > $args['count'] ) {
	$query_args['posts_per_page'] = $args['count'];
	if (!trx_addons_is_off($args['pagination']) && (int)$args['page'] > 1)
		$query_args['paged'] = $args['page'];
	else
		$query_args['offset'] = $args['offset'];
}
$query_args = trx_addons_query_add_sort_order($query_args, $args['orderby'], $args['order']);
$query_args = trx_addons_query_add_posts_and_cats($query_args, $args['ids'], $args['post_type'], $args['cat'], $args['taxonomy']);

$query = new WP_Query( $query_args );
if ((int)$query->found_posts > 0) {
	$args = apply_filters( 'trx_addons_filter_sc_prepare_atts_before_output', $args, $query_args, $query, 'blogger.default' );
	if ($args['count'] > $query->found_posts) $args['count'] = $query->found_posts;
	if ((int)$args['columns'] < 1) $args['columns'] = $args['count'];
	//$args['columns'] = min($args['columns'], $args['count']);
	$args['columns'] = max(1, min(12, (int) $args['columns']));
	$args['slider'] = (int)$args['slider'] > 0 && $args['count'] > $args['columns'];
	$args['slides_space'] = max(0, (int) $args['slides_space']);
	?><div <?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?>
		class="sc_blogger sc_blogger_<?php
			echo esc_attr($args['type']);
			if (!empty($args['class'])) echo ' '.esc_attr($args['class']); 
			?>"<?php
		if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"';
		?>><?php

		trx_addons_sc_show_titles('sc_blogger', $args);
		
		if ($args['slider']) {
			$args['slides_min_width'] = $args['type']=='modern' ? 390 : 220;
			trx_addons_sc_show_slider_wrap_start('sc_blogger', $args);
		} else if ((int)$args['columns'] > 1) {
			?><div class="sc_blogger_columns_wrap sc_item_columns sc_item_posts_container <?php echo esc_attr(trx_addons_get_columns_wrap_class()) . ($args['type']!='plain' ? ' columns_padding_bottom' : ''); ?>"><?php
		} else {
			?><div class="sc_blogger_content sc_item_content sc_item_posts_container"><?php
		}	

		while ( $query->have_posts() ) { $query->the_post();
			trx_addons_get_template_part(array(
											TRX_ADDONS_PLUGIN_SHORTCODES . 'blogger/tpl.'.trx_addons_esc($args['type']).'-item.php',
											TRX_ADDONS_PLUGIN_SHORTCODES . 'blogger/tpl.default-item.php'
											),
											'trx_addons_args_sc_blogger', 
											$args
										);
		}

		wp_reset_postdata();
	
		?></div><?php

		if ($args['slider']) {
			trx_addons_sc_show_slider_wrap_end('sc_blogger', $args);
		}

		trx_addons_sc_show_pagination('sc_blogger', $args, $query);

		trx_addons_sc_show_links('sc_blogger', $args);

	?></div><?php
}
?>