<?php
/**
 * The template for search results with desired blog style
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

// Search page setup
// (uncomment lines with freightco_storage_set_array2() calls if you want to override the relevant settings from Theme Options)

// Blog style:
// Replace last parameter with one of values: excerpt | chess_N | classic_N | masonry_N | portfolio_N | gallery_N
// where N - columns number from 2 to 4 (for chess also available 1 column)
freightco_storage_set_array2('options', 'blog_style', 'val', 'excerpt');

// Sidebar position:
// Replace last parameter with one of values: none | left | right
freightco_storage_set_array2('options', 'sidebar_position_blog', 'val', 'none');

get_template_part( 'index' );
?>