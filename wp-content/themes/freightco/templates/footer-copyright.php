<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap<?php
				if (!freightco_is_inherit(freightco_get_theme_option('copyright_scheme')))
					echo ' scheme_' . esc_attr(freightco_get_theme_option('copyright_scheme'));
 				?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				$freightco_copyright = freightco_get_theme_option('copyright');
				if (!empty($freightco_copyright)) {
					// Replace {{Y}} or {Y} with the current year
					$freightco_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $freightco_copyright);
					// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
					$freightco_copyright = freightco_prepare_macros($freightco_copyright);
					// Display copyright
					echo wp_kses(nl2br($freightco_copyright), 'freightco_kses_content' );
				}
			?></div>
		</div>
	</div>
</div>
