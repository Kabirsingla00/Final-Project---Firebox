<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('freightco_mailchimp_get_css')) {
	add_filter('freightco_filter_get_css', 'freightco_mailchimp_get_css', 10, 2);
	function freightco_mailchimp_get_css($css, $args) {
		
		if (isset($css['fonts']) && isset($args['fonts'])) {
			$fonts = $args['fonts'];
			$css['fonts'] .= <<<CSS
form.mc4wp-form .mc4wp-form-fields input[type="email"] {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
form.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

CSS;
		}		

		if (isset($css['vars']) && isset($args['vars'])) {
			$vars = $args['vars'];
			
			$css['vars'] .= <<<CSS


CSS;
		}

		
		if (isset($css['colors']) && isset($args['colors'])) {
			$colors = $args['colors'];
			$css['colors'] .= <<<CSS


form.mc4wp-form .mc4wp-form-fields .form_title_2color,
form.mc4wp-form .mc4wp-form-fields .form_title_2color:after {
    color: {$colors['extra_bg_color']};
}

form.mc4wp-form .mc4wp-form-fields .form_title_2color span {
    color: {$colors['inverse_dark']};
}

form.mc4wp-form .mc4wp-form-fields input,
form.mc4wp-form .mc4wp-form-fields input.filled,
form.mc4wp-form .mc4wp-form-fields input:focus,
form.mc4wp-form .mc4wp-form-fields input:hover {
    color: {$colors['alter_text']};
    background-color: {$colors['extra_bg_color']};
}

form.mc4wp-form input::-webkit-input-placeholder {color:{$colors['alter_text']}; opacity: 1;}
form.mc4wp-form input::-moz-placeholder          {color:{$colors['alter_text']}; opacity: 1;}/* Firefox 19+ */
form.mc4wp-form input:-moz-placeholder           {color:{$colors['alter_text']}; opacity: 1;}/* Firefox 18- */
form.mc4wp-form input:-ms-input-placeholder      {color:{$colors['alter_text']}; opacity: 1;}

form.mc4wp-form .mc4wp-form-fields input[type="submit"] {
    color: {$colors['text_dark']};
    background-color: {$colors['alter_dark']};
}
form.mc4wp-form .mc4wp-form-fields input[type="submit"]:hover {
    color: {$colors['alter_dark']};
    background-color: {$colors['text_dark']};
}
form.mc4wp-form .mc4wp-form-fields .form_icon_abs {
    color: {$colors['inverse_dark']};
}






form.mc4wp-form .mc4wp-alert {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bg_color']};
	color: {$colors['text_dark']};
}

CSS;
		}

		return $css;
	}
}
?>