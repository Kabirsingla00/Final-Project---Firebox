<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.14
 */
$freightco_header_video = freightco_get_header_video();
$freightco_embed_video = '';
if (!empty($freightco_header_video) && !freightco_is_from_uploads($freightco_header_video)) {
	if (freightco_is_youtube_url($freightco_header_video) && preg_match('/[=\/]([^=\/]*)$/', $freightco_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$freightco_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($freightco_header_video) . '[/embed]' ));
			$freightco_embed_video = freightco_make_video_autoplay($freightco_embed_video);
		} else {
			$freightco_header_video = str_replace('/watch?v=', '/embed/', $freightco_header_video);
			$freightco_header_video = freightco_add_to_url($freightco_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$freightco_embed_video = '<iframe src="' . esc_url($freightco_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php freightco_show_layout($freightco_embed_video); ?></div><?php
	}
}
?>