<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'freightco_sop_get_css' ) ) {
	add_filter( 'freightco_filter_get_css', 'freightco_sop_get_css', 10, 2 );
	function freightco_sop_get_css($css, $args) {
		if (isset($css['fonts']) && isset($args['fonts'])) {
			$fonts = $args['fonts'];
			$css['fonts'] .= <<<CSS
.so-panel .widget-title {
	{$fonts['h5_font-family']}
	{$fonts['h5_font-size']}
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}
CSS;
		}

		if (isset($css['colors']) && isset($args['colors'])) {
			$colors = $args['colors'];
			$css['colors'] .= <<<CSS

/* Row and columns */
.scheme_self.panel-row-style,
.scheme_self.panel-cell-style,
.scheme_self.panel-widget-style {
	color: {$colors['text']};
}

.scheme_self.panel-widget-style {
	background-color: {$colors['bg_color']};
}
.scheme_self.siteorigin-panels-stretch.panel-row-style[data-siteorigin-parallax]:before {
	background-color: {$colors['bg_color_07']};
}
CSS;
		}
		
		return $css;
	}
}
?>