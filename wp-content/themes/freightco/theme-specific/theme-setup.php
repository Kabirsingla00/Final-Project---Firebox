<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0.22
 */

if (!defined("FREIGHTCO_THEME_FREE"))		define("FREIGHTCO_THEME_FREE", false);
if (!defined("FREIGHTCO_THEME_FREE_WP"))	define("FREIGHTCO_THEME_FREE_WP", false);

// Theme storage
$FREIGHTCO_STORAGE = array(
	// Theme required plugin's slugs
	'required_plugins' => array_merge(

		// List of plugins for both - FREE and PREMIUM versions
		//-----------------------------------------------------
		array(
			// Required plugins
			// DON'T COMMENT OR REMOVE NEXT LINES!
			'trx_addons'					=> esc_html__('ThemeREX Addons', 'freightco'),
			
			// Recommended (supported) plugins fot both (lite and full) versions
			// If plugin not need - comment (or remove) it
			'elementor'						=> esc_html__('Elementor', 'freightco'),
			'contact-form-7'				=> esc_html__('Contact Form 7', 'freightco'),
			'mailchimp-for-wp'				=> esc_html__('MailChimp for WP', 'freightco'),
			'wp-gdpr-compliance' 			=> esc_html__( 'Cookie Information', 'freightco' )
		),

		// List of plugins for the FREE version only
		//-----------------------------------------------------
		FREIGHTCO_THEME_FREE 
			? array(
					// Recommended (supported) plugins for the FREE (lite) version
					'siteorigin-panels'			=> esc_html__('SiteOrigin Panels', 'freightco'),
					) 

		// List of plugins for the PREMIUM version only
		//-----------------------------------------------------
			: array(
					// Recommended (supported) plugins for the PRO (full) version
					// If plugin not need - comment (or remove) it
					'calculated-fields-form'	=> esc_html__('Calculated Fields Form', 'freightco'),
					'essential-grid'			=> esc_html__('Essential Grid', 'freightco'),

					'sitepress-multilingual-cms'=> esc_html__('WPML - Sitepress Multilingual CMS', 'freightco'),
					'trx_updater'				=> esc_html__('ThemeRex Updater plugin', 'freightco'),
					)
	),

	// Key validator: market[env|loc]-vendor[axiom|ancora|themerex]
	'theme_pro_key'		=> FREIGHTCO_THEME_FREE 
								? 'env-themerex' 
								: '',

	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url'	=> 'http://freightco.themerex.net',
	'theme_doc_url'		=> 'http://freightco.themerex.net/doc',
    'theme_download_url'=> 'https://themeforest.net/item/freightco-transportation-warehousing-wordpress-theme/22105701',

	'theme_support_url'	=> 'themerex.net/support',

	'theme_video_url'	=> 'https://www.youtube.com/channel/UCnFisBimrK2aIE-hnY70kCA',

	// Comma separated slugs of theme-specific categories (for get relevant news in the dashboard widget)
	// (i.e. 'children,kindergarten')
	'theme_categories'  => '',

	// Responsive resolutions
	// Parameters to create css media query: min, max
	'responsive'		=> array(
						// By device
						'desktop'	=> array('min' => 1680),
						'notebook'	=> array('min' => 1280, 'max' => 1679),
						'tablet'	=> array('min' =>  768, 'max' => 1279),
						'mobile'	=> array('max' =>  767),
						// By size
						'xxl'		=> array('max' => 1679),
						'xl'		=> array('max' => 1439),
						'lg'		=> array('max' => 1279),
						'md'		=> array('max' => 1024),
						'sm'		=> array('max' =>  767),
						'sm_wp'		=> array('max' =>  600),
						'xs'		=> array('max' =>  479)
						)
);

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( !function_exists('freightco_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'freightco_customizer_theme_setup1', 1 );
	function freightco_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		freightco_storage_set('settings', array(
			
			'duplicate_options'		=> 'child',		// none  - use separate options for the main and the child-theme
													// child - duplicate theme options from the main theme to the child-theme only
													// both  - sinchronize changes in the theme options between main and child themes

			'customize_refresh'		=> 'auto',		// Refresh method for preview area in the Appearance - Customize:
													// auto - refresh preview area on change each field with Theme Options
													// manual - refresh only obn press button 'Refresh' at the top of Customize frame

			'max_load_fonts'		=> 5,			// Max fonts number to load from Google fonts or from uploaded fonts

			'comment_after_name'	=> true,		// Place 'comment' field before the 'name' and 'email'

			'socials_type'			=> 'icons',		// Type of socials:
													// icons - use font icons to present social networks
													// images - use images from theme's folder trx_addons/css/icons.png

			'icons_type'			=> 'icons',		// Type of other icons:
													// icons - use font icons to present icons
													// images - use images from theme's folder trx_addons/css/icons.png

			'icons_selector'		=> 'internal',	// Icons selector in the shortcodes:
													// vc (default) - standard VC or Elementor's icons selector (very slow and don't support images)
													// internal - internal popup with plugin's or theme's icons list (fast)
			'check_min_version'		=> true,		// Check if exists a .min version of .css and .js and return path to it
													// instead the path to the original file
													// (if debug_mode is off and modification time of the original file < time of the .min file)
			'autoselect_menu'		=> false,		// Show any menu if no menu selected in the location 'main_menu'
													// (for example, the theme is just activated)
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
			
			'tgmpa_upload'			=> false,		// Allow upload not pre-packaged plugins via TGMPA
			
			'allow_no_image'		=> false,		// Allow use image placeholder if no image present in the blog, related posts, post navigation, etc.

			'separate_schemes'		=> true, 		// Save color schemes to the separate files __color_xxx.css (true) or append its to the __custom.css (false)

			'allow_fullscreen'		=> false 		// Allow cases 'fullscreen' and 'fullwide' for the body style in the Theme Options
													// In the Page Options this styles are present always (can be removed if filter 'freightco_filter_allow_fullscreen' return false)
		));


		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		
		freightco_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Asap',
				'family' => 'sans-serif',
				'styles' => '400,400i,500,500i,600,600i,700,700i'		// Parameter 'style' used only for the Google fonts
				)
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		freightco_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		// Attention! Font name in the parameter 'font-family' will be enclosed in the quotes and no spaces after comma!

		freightco_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'freightco'),
				'description'		=> esc_html__('Font settings of the main text of the site. Attention! For correct display of the site on mobile devices, use only units "rem", "em" or "ex"', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.2em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '5em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.8em',
				'margin-bottom'		=> '0.43em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '3.75em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.03em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.07em',
				'margin-bottom'		=> '0.53em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '3.125em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.08em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.16em',
				'margin-bottom'		=> '0.55em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '2.188em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.14em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.48em',
				'margin-bottom'		=> '0.68em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '1.625em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.15em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.77em',
				'margin-bottom'		=> '0.78em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '1.125em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.33em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2.2em',
				'margin-bottom'		=> '0.9em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'freightco'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '1.875em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-0.75px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '14px',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'freightco'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',	// Attention! Firefox don't allow line-height less then 1.5em in the select
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'freightco'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'freightco'),
				'description'		=> esc_html__('Font settings of the main menu items', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'freightco'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'freightco'),
				'font-family'		=> '"Asap",sans-serif',
				'font-size' 		=> '1.143em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		freightco_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'freightco'),
							'description'	=> esc_html__('Colors of the main content area', 'freightco')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'freightco'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'freightco')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'freightco'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'freightco')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'freightco'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'freightco')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'freightco'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'freightco')
							),
			)
		);
		freightco_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'freightco'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'freightco')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'freightco'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'freightco')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'freightco'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'freightco')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'freightco'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'freightco')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'freightco'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'freightco')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'freightco'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'freightco')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'freightco'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'freightco')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'freightco'),
							'description'	=> esc_html__('Color of the links inside this block', 'freightco')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'freightco'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'freightco')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'freightco'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'freightco')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'freightco'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'freightco')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'freightco'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'freightco')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'freightco'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'freightco')
							)
			)
		);
		freightco_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'freightco'),
				'internal' => true,
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#ffffff', //
					'bd_color'			=> '#d8d9dc', //
		
					// Text and links colors
					'text'				=> '#989ea6', //
					'text_light'		=> '#333537', //
					'text_dark'			=> '#333537', //
					'text_link'			=> '#f0b913', //
					'text_hover'		=> '#333537', //
					'text_link2'		=> '#333537', //
					'text_hover2'		=> '#f0b913', //
					'text_link3'		=> '#ddb837',
					'text_hover3'		=> '#eec432',
		
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#333537', //
					'alter_bg_hover'	=> '#ebeff3', //
					'alter_bd_color'	=> '#4b4c4e', //
					'alter_bd_hover'	=> '#262829', //
					'alter_text'		=> '#989ea6', //
					'alter_light'		=> '#989ea6', //
					'alter_dark'		=> '#ffffff', //
					'alter_link'		=> '#f0b913', //
					'alter_hover'		=> '#ffffff', //
					'alter_link2'		=> '#333537', //
					'alter_hover2'		=> '#ffffff', //
					'alter_link3'		=> '#bac0c9', //
					'alter_hover3'		=> '#ddb837',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#262829', //
					'extra_bg_hover'	=> '#f0b913', //
					'extra_bd_color'	=> '#343434', //
					'extra_bd_hover'	=> '#494a4c', //
					'extra_text'		=> '#989ea6', //
					'extra_light'		=> '#989ea6', //
					'extra_dark'		=> '#ffffff', //
					'extra_link'		=> '#f0b913', //
					'extra_hover'		=> '#ffffff', //
					'extra_link2'		=> '#bec4cc', //
					'extra_hover2'		=> '#f0b913',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#262829', //
					'input_bg_hover'	=> '#262829', //
					'input_bd_color'	=> '#e7eaed',
					'input_bd_hover'	=> '#e0e0e0',
					'input_text'		=> '#ffffff', //
					'input_light'		=> '#4b4c4e', //
					'input_dark'		=> '#ffffff', //
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#000000',
					'inverse_bd_hover'	=> '#000000',
					'inverse_text'		=> '#ffffff', //
					'inverse_light'		=> '#333333',
					'inverse_dark'		=> '#333537', //
					'inverse_link'		=> '#ffffff', //
					'inverse_hover'		=> '#333537' //
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'freightco'),
				'internal' => true,
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#262829', //
					'bd_color'			=> '#4b4c4e', //
		
					// Text and links colors
					'text'				=> '#989ea6', //
					'text_light'		=> '#ffffff', //
					'text_dark'			=> '#ffffff', //
					'text_link'			=> '#f0b913', //
					'text_hover'		=> '#ffffff', //
					'text_link2'		=> '#ffffff', //
					'text_hover2'		=> '#333537', //
					'text_link3'		=> '#ddb837',
					'text_hover3'		=> '#eec432',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#ffffff', //
					'alter_bg_hover'	=> '#eaecee', //
					'alter_bd_color'	=> '#d8d9dc', //
					'alter_bd_hover'	=> '#4a4a4a',
					'alter_text'		=> '#989ea6', //
					'alter_light'		=> '#989ea6', //
					'alter_dark'		=> '#333537', //
					'alter_link'		=> '#f0b913', //
					'alter_hover'		=> '#333537', //
					'alter_link2'		=> '#f0b913',
					'alter_hover2'		=> '#ffffff', //
					'alter_link3'		=> '#bac0c9', //
					'alter_hover3'		=> '#ddb837',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#ffffff', //
					'extra_bg_hover'	=> '#f3f5f7', //
					'extra_bd_color'	=> '#e5e5e5', //
					'extra_bd_hover'	=> '#4a4a4a',
					'extra_text'		=> '#333333', //
					'extra_light'		=> '#ffffff', //
					'extra_dark'		=> '#333537', //
					'extra_link'		=> '#f0b913', //
					'extra_hover'		=> '#f0b913',
					'extra_link2'		=> '#bec4cc', //
					'extra_hover2'		=> '#f0b913',
					'extra_link3'		=> '#f0b913',
					'extra_hover3'		=> '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#333537', //
					'input_bg_hover'	=> '#333537', //
					'input_bd_color'	=> '#2e2d32',
					'input_bd_hover'	=> '#353535',
					'input_text'		=> '#ffffff', //
					'input_light'		=> '#5f5f5f',
					'input_dark'		=> '#ffffff', // 
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#000000',
					'inverse_bd_hover'	=> '#000000',
					'inverse_text'		=> '#ffffff', //
					'inverse_light'		=> '#333537', //
					'inverse_dark'		=> '#333537', //
					'inverse_link'		=> '#ffffff', //
					'inverse_hover'		=> '#333537'  //
				)
			)
		
		));
		
		// Simple schemes substitution
		freightco_storage_set('schemes_simple', array(
			// Main color	// Slave elements and it's darkness koef.
			'text_link'		=> array('alter_hover' => 1,	'extra_link' => 1, 'inverse_bd_color' => 0.85, 'inverse_bd_hover' => 0.7),
			'text_hover'	=> array('alter_link' => 1,		'extra_hover' => 1),
			'text_link2'	=> array('alter_hover2' => 1,	'extra_link2' => 1),
			'text_hover2'	=> array('alter_link2' => 1,	'extra_hover2' => 1),
			'text_link3'	=> array('alter_hover3' => 1,	'extra_link3' => 1),
			'text_hover3'	=> array('alter_link3' => 1,	'extra_hover3' => 1)
		));

		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		freightco_storage_set('scheme_colors_add', array(
			'bg_color_0'		=> array('color' => 'bg_color',			'alpha' => 0),
			'bg_color_02'		=> array('color' => 'bg_color',			'alpha' => 0.2),
			'bg_color_07'		=> array('color' => 'bg_color',			'alpha' => 0.7),
			'bg_color_08'		=> array('color' => 'bg_color',			'alpha' => 0.8),
			'bg_color_09'		=> array('color' => 'bg_color',			'alpha' => 0.9),
			'bd_color_068'		=> array('color' => 'bd_color',			'alpha' => 0.68),
			'alter_bg_color_02'	=> array('color' => 'alter_bg_color',	'alpha' => 0.2),
			'alter_bg_color_04'	=> array('color' => 'alter_bg_color',	'alpha' => 0.4),
			'alter_bg_color_06'	=> array('color' => 'alter_bg_color',	'alpha' => 0.6),
			'alter_bg_color_07'	=> array('color' => 'alter_bg_color',	'alpha' => 0.7),
			'alter_bg_color_08'	=> array('color' => 'alter_bg_color',	'alpha' => 0.8),
			'alter_bg_color_09'	=> array('color' => 'alter_bg_color',	'alpha' => 0.9),
			'alter_bd_color_02'	=> array('color' => 'alter_bd_color',	'alpha' => 0.2),
			'alter_link_02'		=> array('color' => 'alter_link',		'alpha' => 0.2),
			'alter_link_07'		=> array('color' => 'alter_link',		'alpha' => 0.7),
			'alter_link_08'		=> array('color' => 'alter_link',		'alpha' => 0.8),
			'alter_link_085'		=> array('color' => 'alter_link',		'alpha' => 0.85),
			'extra_bg_color_07'	=> array('color' => 'extra_bg_color',	'alpha' => 0.7),
			'extra_bg_color_09'	=> array('color' => 'extra_bg_color',	'alpha' => 0.9),
			'extra_bg_color_092'	=> array('color' => 'extra_bg_color',	'alpha' => 0.92),
			'extra_bg_hover_085'	=> array('color' => 'extra_bg_hover',	'alpha' => 0.85),
			'extra_link_02'		=> array('color' => 'extra_link',		'alpha' => 0.2),
			'extra_link_03'		=> array('color' => 'extra_link',		'alpha' => 0.3),
			'extra_link_07'		=> array('color' => 'extra_link',		'alpha' => 0.7),
			'text_dark_06'		=> array('color' => 'text_dark',		'alpha' => 0.6),
			'text_dark_07'		=> array('color' => 'text_dark',		'alpha' => 0.7),
			'text_dark_09'		=> array('color' => 'text_dark',		'alpha' => 0.9),
			'text_link_02'		=> array('color' => 'text_link',		'alpha' => 0.2),
			'text_link_07'		=> array('color' => 'text_link',		'alpha' => 0.7),
			'text_link_085'		=> array('color' => 'text_link',		'alpha' => 0.85),
			'input_bg_color_07'		=> array('color' => 'input_bg_color',		'alpha' => 0.7),
			'text_link_blend'	=> array('color' => 'text_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5),
			'alter_link_blend'	=> array('color' => 'alter_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5)
		));
		
		// Parameters to set order of schemes in the css
		freightco_storage_set('schemes_sorted', array(
													'color_scheme', 'header_scheme', 'footer_scheme'
													));
		
		
		// -----------------------------------------------------------------
		// -- Theme specific thumb sizes
		// -----------------------------------------------------------------
		freightco_storage_set('theme_thumbs', apply_filters('freightco_filter_add_thumb_sizes', array(
			// Width of the image is equal to the content area width (without sidebar)
			// Height is fixed
			'freightco-thumb-huge'		=> array(
												'size'	=> array(1170, 628, true),
												'title' => esc_html__( 'Huge image', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-huge'
												),
			// Width of the image is equal to the content area width (with sidebar)
			// Height is fixed
			'freightco-thumb-big' 		=> array(
												'size'	=> array( 745, 400, true),
												'title' => esc_html__( 'Large image', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-big'
												),

			// Width of the image is equal to the 1/3 of the content area width (without sidebar)
			// Height is fixed
			'freightco-thumb-med' 		=> array(
												'size'	=> array( 370, 198, true),
												'title' => esc_html__( 'Medium image', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-medium'
												),

			// Small square image (for avatars in comments, etc.)
			'freightco-thumb-tiny' 		=> array(
												'size'	=> array(  90,  90, true),
												'title' => esc_html__( 'Small square avatar', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-tiny'
												),

			// related image
			'freightco-thumb-relimg' 		=> array(
												'size'	=> array( 360, 275, true),
												'title' => esc_html__( 'Related image', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-relimg'
												),

			// related image
			'freightco-thumb-servimg' 		=> array(
												'size'	=> array( 360, 202, true),
												'title' => esc_html__( 'Service image', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-servimg'
												),

			// related image
			'freightco-thumb-vidimg' 		=> array(
												'size'	=> array( 560, 316, true),
												'title' => esc_html__( 'Video image', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-vidimg'
												),

			// Width of the image is equal to the content area width (with sidebar)
			// Height is proportional (only downscale, not crop)
			'freightco-thumb-masonry-big' => array(
												'size'	=> array( 760,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry Large (scaled)', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-masonry-big'
												),

			// Width of the image is equal to the 1/3 of the full content area width (without sidebar)
			// Height is proportional (only downscale, not crop)
			'freightco-thumb-masonry'		=> array(
												'size'	=> array( 370,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry (scaled)', 'freightco' ),
												'subst'	=> 'trx_addons-thumb-masonry'
												)
			))
		);
	}
}




//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'freightco_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'freightco_importer_set_options', 9 );
	function freightco_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url(freightco_get_protocol() . '://demofiles.themerex.net/freightco/');
			// Required plugins
			$options['required_plugins'] = array_keys(freightco_storage_get('required_plugins'));
			// Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 3;
			// Default demo
			$options['files']['default']['title'] = esc_html__('FreightCo Demo', 'freightco');
			$options['files']['default']['domain_dev'] = esc_url(freightco_get_protocol().'://freightco.dv.themerex.net');		// Developers domain
			$options['files']['default']['domain_demo']= esc_url('http://freightco.themerex.net');		// Demo-site domain
			// If theme need more demo - just copy 'default' and change required parameter

			// Banners
			$options['banners'] = array(
				array(
					'image' => freightco_get_file_url('theme-specific/theme-about/images/frontpage.png'),
					'title' => esc_html__('Front Page Builder', 'freightco'),
					'content' => wp_kses(__("Create your front page right in the WordPress Customizer. There's no need in any page builder. Simply enable/disable sections, fill them out with content, and customize to your liking.", 'freightco'), 'freightco_kses_content' ),
					'link_url' => esc_url('//www.youtube.com/watch?v=VT0AUbMl_KA'),
					'link_caption' => esc_html__('Watch Video Introduction', 'freightco'),
					'duration' => 20
					),
				array(
					'image' => freightco_get_file_url('theme-specific/theme-about/images/layouts.png'),
					'title' => esc_html__('Layouts Builder', 'freightco'),
					'content' => wp_kses(__('Use Layouts Builder to create and customize header and footer styles for your website. With a flexible page builder interface and custom shortcodes, you can create as many header and footer layouts as you want with ease.', 'freightco'), 'freightco_kses_content' ),
					'link_url' => esc_url('//www.youtube.com/watch?v=pYhdFVLd7y4'),
					'link_caption' => esc_html__('Learn More', 'freightco'),
					'duration' => 20
					),
				array(
					'image' => freightco_get_file_url('theme-specific/theme-about/images/documentation.png'),
					'title' => esc_html__('Read Full Documentation', 'freightco'),
					'content' => wp_kses(__('Need more details? Please check our full online documentation for detailed information on how to use FreightCo.', 'freightco'), 'freightco_kses_content' ),
					'link_url' => esc_url(freightco_storage_get('theme_doc_url')),
					'link_caption' => esc_html__('Online Documentation', 'freightco'),
					'duration' => 15
					),
				array(
					'image' => freightco_get_file_url('theme-specific/theme-about/images/video-tutorials.png'),
					'title' => esc_html__('Video Tutorials', 'freightco'),
					'content' => wp_kses(__('No time for reading documentation? Check out our video tutorials and learn how to customize FreightCo in detail.', 'freightco'), 'freightco_kses_content' ),
					'link_url' => esc_url(freightco_storage_get('theme_video_url')),
					'link_caption' => esc_html__('Video Tutorials', 'freightco'),
					'duration' => 15
					),
				array(
					'image' => freightco_get_file_url('theme-specific/theme-about/images/studio.png'),
					'title' => esc_html__('Website Customization', 'freightco'),
					'content' => wp_kses(__("Need a website fast? Order our custom service, and we'll build a website based on this theme for a very fair price. We can also implement additional functionality such as website translation, setting up WPML, and much more.", 'freightco'), 'freightco_kses_content' ),
					'link_url' => esc_url('//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall'),
					'link_caption' => esc_html__('Contact Us', 'freightco'),
					'duration' => 25
					)
				);
		}
		return $options;
	}
}



// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('freightco_create_theme_options')) {

	function freightco_create_theme_options() {

		// Message about options override. 
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override = esc_html__('Attention! Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages', 'freightco');
		
		// Color schemes number: if < 2 - hide fields with selectors
		$hide_schemes = count(freightco_storage_get('schemes')) < 2;
		
		freightco_storage_set('options', array(
		
			// 'Logo & Site Identity'
			'title_tagline' => array(
				"title" => esc_html__('Logo & Site Identity', 'freightco'),
				"desc" => '',
				"priority" => 10,
				"type" => "section"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo in the header', 'freightco'),
				"desc" => '',
				"priority" => 20,
				"type" => "info",
				),
			'logo_text' => array(
				"title" => esc_html__('Use Site Name as Logo', 'freightco'),
				"desc" => wp_kses_data( __('Use the site title and tagline as a text logo if no image is selected', 'freightco') ),
				"class" => "freightco_column-1_2 freightco_new_row",
				"priority" => 30,
				"std" => 1,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_retina_enabled' => array(
				"title" => esc_html__('Allow retina display logo', 'freightco'),
				"desc" => wp_kses_data( __('Show fields to select logo images for Retina display', 'freightco') ),
				"class" => "freightco_column-1_2",
				"priority" => 40,
				"refresh" => false,
				"std" => 0,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_zoom' => array(
				"title" => esc_html__('Logo zoom', 'freightco'),
				"desc" => wp_kses_data( __("Zoom the logo. 1 - original size. Maximum size of logo depends on the actual size of the picture", 'freightco') ),
				"std" => 1,
				"min" => 0.2,
				"max" => 2,
				"step" => 0.1,
				"refresh" => false,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "slider"
				),
			// Parameter 'logo' was replaced with standard WordPress 'custom_logo'
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'freightco') ),
				"class" => "freightco_column-1_2",
				"priority" => 70,
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile_header' => array(
				"title" => esc_html__('Logo for the mobile header', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile header (if enabled in the section "Header - Header mobile"', 'freightco') ),
				"class" => "freightco_column-1_2 freightco_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_header_retina' => array(
				"title" => esc_html__('Logo for the mobile header for Retina', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'freightco') ),
				"class" => "freightco_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile' => array(
				"title" => esc_html__('Logo mobile', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile menu', 'freightco') ),
				"class" => "freightco_column-1_2 freightco_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_retina' => array(
				"title" => esc_html__('Logo mobile for Retina', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'freightco') ),
				"class" => "freightco_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "image"
				),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'freightco') ),
				"class" => "freightco_column-1_2 freightco_new_row",
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'freightco') ),
				"class" => "freightco_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"hidden" => true,
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "image"
				),
			
		
		
			// 'General settings'
			'general' => array(
				"title" => esc_html__('General Settings', 'freightco'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 20,
				"type" => "section",
				),

			'general_layout_info' => array(
				"title" => esc_html__('Layout', 'freightco'),
				"desc" => '',
				"type" => "info",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'freightco'),
				"desc" => wp_kses_data( __('Select width of the body content', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'freightco')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => freightco_get_list_body_styles(false),
				"type" => "select"
				),
			'page_width' => array(
				"title" => esc_html__('Page width', 'freightco'),
				"desc" => wp_kses_data( __("Total width of the site content and sidebar (in pixels). If empty - use default width", 'freightco') ),
				"dependency" => array(
					'body_style' => array('boxed', 'wide')
				),
				"std" => 1170,
				"min" => 1000,
				"max" => 1400,
				"step" => 10,
				"refresh" => false,
				"customizer" => 'page',		// SASS name to preview changes 'on fly'
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "slider"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'freightco') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'freightco')
				),
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'freightco'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'freightco')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),

			'general_sidebar_info' => array(
				"title" => esc_html__('Sidebar', 'freightco'),
				"desc" => '',
				"type" => "info",
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'freightco'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'freightco')
				),
				"std" => 'right',
				"options" => array(),
				"type" => "switch"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'freightco'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'freightco')
				),
				"dependency" => array(
					'sidebar_position' => array('left', 'right')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => "select"
				),
			'sidebar_width' => array(
				"title" => esc_html__('Sidebar width', 'freightco'),
				"desc" => wp_kses_data( __("Width of the sidebar (in pixels). If empty - use default width", 'freightco') ),
				"std" => 355,
				"min" => 150,
				"max" => 500,
				"step" => 10,
				"refresh" => false,
				"customizer" => 'sidebar',		// SASS name to preview changes 'on fly'
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "slider"
				),
			'sidebar_gap' => array(
				"title" => esc_html__('Sidebar gap', 'freightco'),
				"desc" => wp_kses_data( __("Gap between content and sidebar (in pixels). If empty - use default gap", 'freightco') ),
				"std" => 70,
				"min" => 0,
				"max" => 100,
				"step" => 1,
				"refresh" => false,
				"customizer" => 'gap',		// SASS name to preview changes 'on fly'
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "slider"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'freightco'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'freightco') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),


			'general_widgets_info' => array(
				"title" => esc_html__('Additional widgets', 'freightco'),
				"desc" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info",
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets at the top of the page', 'freightco'),
				"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'freightco')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'freightco'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'freightco')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'freightco'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'freightco')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets at the bottom of the page', 'freightco'),
				"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'freightco')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
				),

			'general_effects_info' => array(
				"title" => esc_html__('Design & Effects', 'freightco'),
				"desc" => '',
				"type" => "info",
				),
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'freightco'),
				"desc" => wp_kses_data( __("Specify the border radius of the form fields and buttons in pixels", 'freightco') ),
				"std" => 0,
				"min" => 0,
				"max" => 20,
				"hidden" => true,
				"step" => 1,
				"refresh" => false,
				"customizer" => 'rad',		// SASS name to preview changes 'on fly'
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "slider"
				),

			'general_misc_info' => array(
				"title" => esc_html__('Miscellaneous', 'freightco'),
				"desc" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info",
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'freightco'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'freightco') ),
				"std" => 0,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'freightco'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'freightco') ),
                "std"   => wp_kses( __( 'I agree that my submitted data is being collected and stored.', 'freightco'), 'freightco_kses_content'  ),
                "type"  => "text"
            ),
		
		
			// 'Header'
			'header' => array(
				"title" => esc_html__('Header', 'freightco'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 30,
				"type" => "section"
				),

			'header_style_info' => array(
				"title" => esc_html__('Header style', 'freightco'),
				"desc" => '',
				"type" => "info"
				),
			'header_type' => array(
				"title" => esc_html__('Header style', 'freightco'),
				"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'freightco')
				),
				"std" => 'default',
				"options" => freightco_get_list_header_footer_types(),
				"type" => FREIGHTCO_THEME_FREE || !freightco_exists_trx_addons() ? "hidden" : "switch"
				),
			'header_style' => array(
				"title" => esc_html__('Select custom layout', 'freightco'),
				"desc" => wp_kses( __("Select custom header from Layouts Builder", 'freightco'), 'freightco_kses_content' ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'freightco')
				),
				"dependency" => array(
					'header_type' => array('custom')
				),
				"std" => FREIGHTCO_THEME_FREE ? 'header-custom-elementor-header-default' : 'header-custom-header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'freightco'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'freightco')
				),
				"std" => 'default',
				"options" => array(),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'freightco'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'freightco') ),
				"override" => array(
					'mode' => 'none',
					'section' => esc_html__('Header', 'freightco')
				),
				"hidden" => true,
				"std" => 0,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_zoom' => array(
				"title" => esc_html__('Header zoom', 'freightco'),
				"desc" => wp_kses_data( __("Zoom the header title. 1 - original size", 'freightco') ),
				"std" => 1,
				"min" => 0.3,
				"max" => 2,
				"step" => 0.1,
				"refresh" => false,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "slider"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwidth', 'freightco'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'freightco')
				),
				"dependency" => array(
					'header_type' => array('default')
				),
				"std" => 1,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_widgets_info' => array(
				"title" => esc_html__('Header widgets', 'freightco'),
				"desc" => wp_kses_data( __('Here you can place a widget slider, advertising banners, etc.', 'freightco') ),
				"type" => "info"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'freightco'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'freightco'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'freightco') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'freightco'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'freightco')
				),
				"dependency" => array(
					'header_type' => array('default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => freightco_get_list_range(0,6),
				"type" => "select"
				),

			'menu_info' => array(
				"title" => esc_html__('Main menu', 'freightco'),
				"desc" => wp_kses_data( __('Select main menu style, position and other parameters', 'freightco') ),
				"hidden" => true,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'freightco'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'freightco') ),
				"override" => array(
					'mode' => 'none',
					'section' => esc_html__('Header', 'freightco')
				),
				"hidden" => true,
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'freightco')
				),
				"type" => FREIGHTCO_THEME_FREE || !freightco_exists_trx_addons() ? "hidden" : "switch"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'freightco'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'freightco') ),
				"override" => array(
					'mode' => 'none',
					'section' => esc_html__('Header', 'freightco')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"hidden" => true,
				"std" => 0,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'freightco'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'freightco') ),
				"override" => array(
					'mode' => 'none',
					'section' => esc_html__('Header', 'freightco')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"hidden" => true,
				"std" => 1,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'freightco'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'freightco') ),
				"hidden" => true,
				"std" => 1,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_image_info' => array(
				"title" => esc_html__('Header image', 'freightco'),
				"desc" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'freightco'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'freightco') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'freightco')
				),
				"std" => 0,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_mobile_info' => array(
				"title" => esc_html__('Mobile header', 'freightco'),
				"desc" => wp_kses_data( __("Configure the mobile version of the header", 'freightco') ),
				"priority" => 500,
				"dependency" => array(
					'header_type' => array('default')
				),
				"hidden" => true,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info"
				),
			'header_mobile_enabled' => array(
				"title" => esc_html__('Enable the mobile header', 'freightco'),
				"desc" => wp_kses_data( __("Use the mobile version of the header (if checked) or relayout the current header on mobile devices", 'freightco') ),
				"dependency" => array(
					'header_type' => array('default')
				),
				"hidden" => true,
				"std" => 0,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_additional_info' => array(
				"title" => esc_html__('Additional info', 'freightco'),
				"desc" => wp_kses_data( __('Additional info to show at the top of the mobile header', 'freightco') ),
				"std" => '',
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"hidden" => true,
				"refresh" => false,
				"teeny" => false,
				"rows" => 20,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "text_editor"
				),
			'header_mobile_hide_info' => array(
				"title" => esc_html__('Hide additional info', 'freightco'),
				"std" => 0,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"hidden" => true,
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_logo' => array(
				"title" => esc_html__('Hide logo', 'freightco'),
				"std" => 0,
				"hidden" => true,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_login' => array(
				"title" => esc_html__('Hide login/logout', 'freightco'),
				"std" => 0,
				"hidden" => true,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_search' => array(
				"title" => esc_html__('Hide search', 'freightco'),
				"std" => 0,
				"hidden" => true,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_cart' => array(
				"title" => esc_html__('Hide cart', 'freightco'),
				"std" => 0,
				"hidden" => true,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
				),


		
			// 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'freightco'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 50,
				"type" => "section"
				),
			'footer_type' => array(
				"title" => esc_html__('Footer style', 'freightco'),
				"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'freightco')
				),
				"std" => 'default',
				"options" => freightco_get_list_header_footer_types(),
				"type" => FREIGHTCO_THEME_FREE || !freightco_exists_trx_addons() ? "hidden" : "switch"
				),
			'footer_style' => array(
				"title" => esc_html__('Select custom layout', 'freightco'),
				"desc" => wp_kses( __("Select custom footer from Layouts Builder", 'freightco'), 'freightco_kses_content' ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'freightco')
				),
				"dependency" => array(
					'footer_type' => array('custom')
				),
				"std" => FREIGHTCO_THEME_FREE ? 'footer-custom-elementor-footer-default' : 'footer-custom-footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'freightco'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'freightco')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'freightco'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'freightco')
				),
				"dependency" => array(
					'footer_type' => array('default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => freightco_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwidth', 'freightco'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'freightco') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'freightco')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'freightco'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'freightco') ),
				'refresh' => false,
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'freightco') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1)
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'freightco') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1),
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'freightco'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'freightco') ),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => !freightco_exists_trx_addons() ? "hidden" : "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'freightco'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'freightco') ),
				"translate" => true,
				"std" => esc_html__('Copyright &copy; {Y} by ThemeREX. All rights reserved.', 'freightco'),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
			
		
		
			// 'Blog'
			'blog' => array(
				"title" => esc_html__('Blog', 'freightco'),
				"desc" => wp_kses_data( __('Options of the the blog archive', 'freightco') ),
				"priority" => 70,
				"type" => "panel",
				),
		
				// Blog - Posts page
				'blog_general' => array(
					"title" => esc_html__('Posts page', 'freightco'),
					"desc" => wp_kses_data( __('Style and components of the blog archive', 'freightco') ),
					"type" => "section",
					),
				'blog_general_info' => array(
					"title" => esc_html__('General settings', 'freightco'),
					"desc" => '',
					"type" => "info",
					),
				'blog_style' => array(
					"title" => esc_html__('Blog style', 'freightco'),
					"desc" => '',
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => 'excerpt',
					"options" => array(),
					"type" => "select"
					),
				'first_post_large' => array(
					"title" => esc_html__('First post large', 'freightco'),
					"desc" => wp_kses_data( __('Make your first post stand out by making it bigger', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('classic', 'masonry')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				"blog_content" => array( 
					"title" => esc_html__('Posts content', 'freightco'),
					"desc" => wp_kses_data( __("Display either post excerpts or the full post content", 'freightco') ),
					"std" => "excerpt",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"options" => array(
						'excerpt'	=> esc_html__('Excerpt',	'freightco'),
						'fullpost'	=> esc_html__('Full post',	'freightco')
					),
					"type" => "switch"
					),
				'excerpt_length' => array(
					"title" => esc_html__('Excerpt length', 'freightco'),
					"desc" => wp_kses_data( __("Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged", 'freightco') ),
					"dependency" => array(
						'blog_style' => array('excerpt'),
						'blog_content' => array('excerpt')
					),
					"std" => 38,
					"type" => "text"
					),
				'blog_columns' => array(
					"title" => esc_html__('Blog columns', 'freightco'),
					"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'freightco') ),
					"std" => 2,
					"options" => freightco_get_list_range(2,4),
					"type" => "hidden"
					),
				'post_type' => array(
					"title" => esc_html__('Post type', 'freightco'),
					"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"linked" => 'parent_cat',
					"refresh" => false,
					"hidden" => true,
					"std" => 'post',
					"options" => array(),
					"type" => "select"
					),
				'parent_cat' => array(
					"title" => esc_html__('Category to show', 'freightco'),
					"desc" => wp_kses_data( __('Select category to show in the blog archive', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"refresh" => false,
					"hidden" => true,
					"std" => '0',
					"options" => array(),
					"type" => "select"
					),
				'posts_per_page' => array(
					"title" => esc_html__('Posts per page', 'freightco'),
					"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"hidden" => true,
					"std" => '',
					"type" => "text"
					),
				"blog_pagination" => array( 
					"title" => esc_html__('Pagination style', 'freightco'),
					"desc" => wp_kses_data( __('Infinite scroll or Page numbers below the posts list', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"std" => "pages",
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"options" => array(
						'pages'	=> esc_html__("Page numbers", 'freightco'),
						'infinite' => esc_html__("Infinite scroll", 'freightco')
					),
					"type" => "select"
					),
				'show_filters' => array(
					"title" => esc_html__('Show filters', 'freightco'),
					"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('portfolio', 'gallery')
					),
					"hidden" => true,
					"std" => 0,
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checkbox"
					),
	
				'blog_sidebar_info' => array(
					"title" => esc_html__('Sidebar', 'freightco'),
					"desc" => '',
					"type" => "info",
					),
				'sidebar_position_blog' => array(
					"title" => esc_html__('Sidebar position', 'freightco'),
					"desc" => wp_kses_data( __('Select position to show sidebar', 'freightco') ),
					"std" => 'right',
					"options" => array(),
					"type" => "switch"
					),
				'sidebar_widgets_blog' => array(
					"title" => esc_html__('Sidebar widgets', 'freightco'),
					"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'freightco') ),
					"dependency" => array(
						'sidebar_position_blog' => array('left', 'right')
					),
					"std" => 'sidebar_widgets',
					"options" => array(),
					"type" => "select"
					),
				'expand_content_blog' => array(
					"title" => esc_html__('Expand content', 'freightco'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'freightco') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
	
	
				'blog_widgets_info' => array(
					"title" => esc_html__('Additional widgets', 'freightco'),
					"desc" => '',
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info",
					),
				'widgets_above_page_blog' => array(
					"title" => esc_html__('Widgets at the top of the page', 'freightco'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'freightco') ),
					"std" => 'hide',
					"options" => array(),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),
				'widgets_above_content_blog' => array(
					"title" => esc_html__('Widgets above the content', 'freightco'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'freightco') ),
					"std" => 'hide',
					"options" => array(),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_content_blog' => array(
					"title" => esc_html__('Widgets below the content', 'freightco'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'freightco') ),
					"std" => 'hide',
					"options" => array(),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_page_blog' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'freightco'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'freightco') ),
					"std" => 'hide',
					"options" => array(),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),

				'blog_advanced_info' => array(
					"title" => esc_html__('Advanced settings', 'freightco'),
					"desc" => '',
					"type" => "info",
					),
				'no_image' => array(
					"title" => esc_html__('Image placeholder', 'freightco'),
					"desc" => wp_kses_data( __('Select or upload an image used as placeholder for posts without a featured image', 'freightco') ),
					"std" => '',
					"type" => "image"
					),
				'time_diff_before' => array(
					"title" => esc_html__('Easy Readable Date Format', 'freightco'),
					"desc" => wp_kses_data( __("For how many days to show the easy-readable date format (e.g. '3 days ago') instead of the standard publication date", 'freightco') ),
					"std" => 5,
					"type" => "text"
					),
				'sticky_style' => array(
					"title" => esc_html__('Sticky posts style', 'freightco'),
					"desc" => wp_kses_data( __('Select style of the sticky posts output', 'freightco') ),
					"std" => 'inherit',
					"options" => array(
						'inherit' => esc_html__('Decorated posts', 'freightco'),
						'columns' => esc_html__('Mini-cards',	'freightco')
					),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),
				"blog_animation" => array( 
					"title" => esc_html__('Animation for the posts', 'freightco'),
					"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => "none",
					"options" => array(),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),
				'meta_parts' => array(
					"title" => esc_html__('Post meta', 'freightco'),
					"desc" => wp_kses_data( __("If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page. Post counters and Share Links are available only if plugin ThemeREX Addons is active", 'freightco') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'date=1|categories=1|counters=1|author=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'freightco'),
						'date'		 => esc_html__('Post date', 'freightco'),
						'author'	 => esc_html__('Post author', 'freightco'),
						'counters'	 => esc_html__('Post counters', 'freightco'),
						'edit'		 => esc_html__('Edit link', 'freightco')
					),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checklist"
				),
				'counters' => array(
					"title" => esc_html__('Post counters', 'freightco'),
					"desc" => wp_kses_data( __("Show only selected counters. Attention! Likes and Views are available only if ThemeREX Addons is active", 'freightco') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'freightco')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|comments=1|likes=1',
					"options" => array(
						'views' => esc_html__('Views', 'freightco'),
						'likes' => esc_html__('Likes', 'freightco'),
						'comments' => esc_html__('Comments', 'freightco')
					),
					"type" => FREIGHTCO_THEME_FREE || !freightco_exists_trx_addons() ? "hidden" : "checklist"
				),

				
				// Blog - Single posts
				'blog_single' => array(
					"title" => esc_html__('Single posts', 'freightco'),
					"desc" => wp_kses_data( __('Settings of the single post', 'freightco') ),
					"type" => "section",
					),
				'hide_featured_on_single' => array(
					"title" => esc_html__('Hide featured image on the single post', 'freightco'),
					"desc" => wp_kses_data( __("Hide featured image on the single post's pages", 'freightco') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'freightco')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'hide_sidebar_on_single' => array(
					"title" => esc_html__('Hide sidebar on the single post', 'freightco'),
					"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'freightco') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'show_post_meta' => array(
					"title" => esc_html__('Show post meta', 'freightco'),
					"desc" => wp_kses_data( __("Display block with post's meta: date, categories, counters, etc.", 'freightco') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'meta_parts_post' => array(
					"title" => esc_html__('Post meta', 'freightco'),
					"desc" => wp_kses_data( __("Meta parts for single posts. Post counters and Share Links are available only if plugin ThemeREX Addons is active", 'freightco') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'freightco') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'date=1|categories=1|counters=1|author=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'freightco'),
						'date'		 => esc_html__('Post date', 'freightco'),
						'author'	 => esc_html__('Post author', 'freightco'),
						'counters'	 => esc_html__('Post counters', 'freightco'),
						'edit'		 => esc_html__('Edit link', 'freightco')
					),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "checklist"
				),
				'counters_post' => array(
					"title" => esc_html__('Post counters', 'freightco'),
					"desc" => wp_kses_data( __("Show only selected counters. Attention! Likes and Views are available only if plugin ThemeREX Addons is active", 'freightco') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|comments=1|likes=1',
					"options" => array(
						'views' => esc_html__('Views', 'freightco'),
						'likes' => esc_html__('Likes', 'freightco'),
						'comments' => esc_html__('Comments', 'freightco')
					),
					"type" => FREIGHTCO_THEME_FREE || !freightco_exists_trx_addons() ? "hidden" : "checklist"
				),
				'show_share_links' => array(
					"title" => esc_html__('Show share links', 'freightco'),
					"desc" => wp_kses_data( __("Display share links on the single post", 'freightco') ),
					"std" => 1,
					"type" => !freightco_exists_trx_addons() ? "hidden" : "checkbox"
					),
				'show_author_info' => array(
					"title" => esc_html__('Show author info', 'freightco'),
					"desc" => wp_kses_data( __("Display block with information about post's author", 'freightco') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'blog_single_related_info' => array(
					"title" => esc_html__('Related posts', 'freightco'),
					"desc" => '',
					"type" => "info",
					),
				'show_related_posts' => array(
					"title" => esc_html__('Show related posts', 'freightco'),
					"desc" => wp_kses_data( __("Show section 'Related posts' on the single post's pages", 'freightco') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'freightco')
					),
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts' => array(
					"title" => esc_html__('Related posts', 'freightco'),
					"desc" => wp_kses_data( __('How many related posts should be displayed in the single post? If 0 - no related posts are shown.', 'freightco') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"hidden" => true,
					"options" => freightco_get_list_range(2,2),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
					),
				'related_columns' => array(
					"title" => esc_html__('Related columns', 'freightco'),
					"desc" => wp_kses_data( __('How many columns should be used to output related posts in the single page (from 2 to 4)?', 'freightco') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"hidden" => true,
					"options" => freightco_get_list_range(2,2),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
					),
				'related_style' => array(
					"title" => esc_html__('Related posts style', 'freightco'),
					"desc" => wp_kses_data( __('Select style of the related posts output', 'freightco') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"hidden" => true,
					"options" => freightco_get_list_styles(2,2),
					"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
					),
			'blog_end' => array(
				"type" => "panel_end",
				),
			
		
		
			// 'Colors'
			'panel_colors' => array(
				"title" => esc_html__('Colors', 'freightco'),
				"desc" => '',
				"priority" => 300,
				"type" => "section"
				),

			'color_schemes_info' => array(
				"title" => esc_html__('Color schemes', 'freightco'),
				"desc" => wp_kses_data( __('Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'freightco') ),
				"hidden" => $hide_schemes,
				"type" => "info",
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'freightco'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'freightco')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'freightco'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'freightco')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Sidemenu Color Scheme', 'freightco'),
				"desc" => '',
				"override" => array(
					'mode' => 'none',
					'section' => esc_html__('Colors', 'freightco')
				),
				"std" => 'inherit',
				"options" => array(),
				"hidden" => true,
				"refresh" => false,
				"type" => $hide_schemes || FREIGHTCO_THEME_FREE ? "hidden" : "switch"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'freightco'),
				"desc" => '',
				"override" => array(
					'mode' => 'none',
					'section' => esc_html__('Colors', 'freightco')
				),
				"std" => 'inherit',
				"options" => array(),
				"hidden" => true,
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'freightco'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'freightco')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),

			'color_scheme_editor_info' => array(
				"title" => esc_html__('Color scheme editor', 'freightco'),
				"desc" => wp_kses_data(__('Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'freightco') ),
				"type" => "info",
				),
			'scheme_storage' => array(
				"title" => esc_html__('Color scheme editor', 'freightco'),
				"desc" => '',
				"std" => '$freightco_get_scheme_storage',
				"refresh" => false,
				"colorpicker" => "tiny",
				"type" => "scheme_editor"
				),


			// 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'freightco'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'freightco') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'freightco')
				),
				"hidden" => true,
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'freightco'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'freightco') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'freightco')
				),
				"hidden" => true,
				"std" => '',
				"type" => FREIGHTCO_THEME_FREE ? "hidden" : "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			// Use huge priority to call render this elements after all options!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"priority" => 10000,
				"type" => "hidden",
				),

			'last_option' => array(		// Need to manually call action to include Tiny MCE scripts
				"title" => '',
				"desc" => '',
				"std" => 1,
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		// -------------------------------------------------------------
		$fonts = array(
		
			// 'Fonts'
			'fonts' => array(
				"title" => esc_html__('Typography', 'freightco'),
				"desc" => '',
				"priority" => 200,
				"type" => "panel"
				),

			// Fonts - Load_fonts
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'freightco'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'freightco') )
						. '<br>'
						. wp_kses_data( __('Attention! Press "Refresh" button to reload preview area after the all fonts are changed', 'freightco') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'freightco'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'freightco') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'freightco') ),
				"class" => "freightco_column-1_3 freightco_new_row",
				"refresh" => false,
				"std" => '$freightco_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=freightco_get_theme_setting('max_load_fonts'); $i++) {
			if (freightco_get_value_gp('page') != 'theme_options') {
				$fonts["load_fonts-{$i}-info"] = array(
					// Translators: Add font's number - 'Font 1', 'Font 2', etc
					"title" => esc_html(sprintf(__('Font %s', 'freightco'), $i)),
					"desc" => '',
					"type" => "info",
					);
			}
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'freightco'),
				"desc" => '',
				"class" => "freightco_column-1_3 freightco_new_row",
				"refresh" => false,
				"std" => '$freightco_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'freightco'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'freightco') )
							: '',
				"class" => "freightco_column-1_3",
				"refresh" => false,
				"std" => '$freightco_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'freightco'),
					'serif' => esc_html__('serif', 'freightco'),
					'sans-serif' => esc_html__('sans-serif', 'freightco'),
					'monospace' => esc_html__('monospace', 'freightco'),
					'cursive' => esc_html__('cursive', 'freightco'),
					'fantasy' => esc_html__('fantasy', 'freightco')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'freightco'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'freightco') )
								. '<br>'
								. wp_kses_data( __('Attention! Each weight and style increase download size! Specify only used weights and styles.', 'freightco') )
							: '',
				"class" => "freightco_column-1_3",
				"refresh" => false,
				"std" => '$freightco_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = freightco_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								// Translators: Add tag's name to make title 'H1 settings', 'P settings', etc.
								: esc_html(sprintf(__('%s settings', 'freightco'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								// Translators: Add tag's name to make description
								: wp_kses_post( sprintf(__('Font settings of the "%s" tag.', 'freightco'), $tag) ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$load_order = 1;
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
					$load_order = 2;		// Load this option's value after all options are loaded (use option 'load_fonts' to build fonts list)
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'freightco'),
						'100' => esc_html__('100 (Light)', 'freightco'), 
						'200' => esc_html__('200 (Light)', 'freightco'), 
						'300' => esc_html__('300 (Thin)',  'freightco'),
						'400' => esc_html__('400 (Normal)', 'freightco'),
						'500' => esc_html__('500 (Semibold)', 'freightco'),
						'600' => esc_html__('600 (Semibold)', 'freightco'),
						'700' => esc_html__('700 (Bold)', 'freightco'),
						'800' => esc_html__('800 (Black)', 'freightco'),
						'900' => esc_html__('900 (Black)', 'freightco')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'freightco'),
						'normal' => esc_html__('Normal', 'freightco'), 
						'italic' => esc_html__('Italic', 'freightco')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'freightco'),
						'none' => esc_html__('None', 'freightco'), 
						'underline' => esc_html__('Underline', 'freightco'),
						'overline' => esc_html__('Overline', 'freightco'),
						'line-through' => esc_html__('Line-through', 'freightco')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'freightco'),
						'none' => esc_html__('None', 'freightco'), 
						'uppercase' => esc_html__('Uppercase', 'freightco'),
						'lowercase' => esc_html__('Lowercase', 'freightco'),
						'capitalize' => esc_html__('Capitalize', 'freightco')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"class" => "freightco_column-1_5",
					"refresh" => false,
					"load_order" => $load_order,
					"std" => '$freightco_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters to Theme Options
		freightco_storage_set_array_before('options', 'panel_colors', $fonts);


		// Add Header Video if WP version < 4.7
		// -----------------------------------------------------
		if (!function_exists('get_header_video_url')) {
			freightco_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'freightco'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'freightco') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'freightco')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}


		// Add option 'logo' if WP version < 4.5
		// or 'custom_logo' if current page is 'Theme Options'
		// ------------------------------------------------------
		if (!function_exists('the_custom_logo') || (isset($_REQUEST['page']) && $_REQUEST['page']=='theme_options')) {
			freightco_storage_set_array_before('options', 'logo_retina', function_exists('the_custom_logo') ? 'custom_logo' : 'logo', array(
				"title" => esc_html__('Logo', 'freightco'),
				"desc" => wp_kses_data( __('Select or upload the site logo', 'freightco') ),
				"class" => "freightco_column-1_2 freightco_new_row",
				"priority" => 60,
				"std" => '',
				"type" => "image"
				)
			);
		}

	}
}


// Returns a list of options that can be overridden for CPT
if (!function_exists('freightco_options_get_list_cpt_options')) {
	function freightco_options_get_list_cpt_options($cpt, $title='') {
		if (empty($title)) $title = ucfirst($cpt);
		return array(
					"header_info_{$cpt}" => array(
						"title" => esc_html__('Header', 'freightco'),
						"desc" => '',
						"type" => "info",
						),
					"header_type_{$cpt}" => array(
						"title" => esc_html__('Header style', 'freightco'),
						"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'freightco') ),
						"std" => 'inherit',
						"options" => freightco_get_list_header_footer_types(true),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
						),
					"header_style_{$cpt}" => array(
						"title" => esc_html__('Select custom layout', 'freightco'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select custom layout to display the site header on the %s pages', 'freightco'), $title) ),
						"dependency" => array(
							"header_type_{$cpt}" => array('custom')
						),
						"std" => 'inherit',
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
						),
					"header_position_{$cpt}" => array(
						"title" => esc_html__('Header position', 'freightco'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select position to display the site header on the %s pages', 'freightco'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
						),
					"header_image_override_{$cpt}" => array(
						"title" => esc_html__('Header image override', 'freightco'),
						"desc" => wp_kses_data( __("Allow override the header image with the post's featured image", 'freightco') ),
						"std" => 'inherit',
						"options" => array(
							'inherit' => esc_html__('Inherit', 'freightco'),
							1 => esc_html__('Yes', 'freightco'),
							0 => esc_html__('No', 'freightco'),
						),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
						),
					"header_widgets_{$cpt}" => array(
						"title" => esc_html__('Header widgets', 'freightco'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select set of widgets to show in the header on the %s pages', 'freightco'), $title) ),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
						
					"sidebar_info_{$cpt}" => array(
						"title" => esc_html__('Sidebar', 'freightco'),
						"desc" => '',
						"type" => "info",
						),
					"sidebar_position_{$cpt}" => array(
						"title" => esc_html__('Sidebar position', 'freightco'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select position to show sidebar on the %s pages', 'freightco'), $title) ),
						"std" => 'left',
						"options" => array(),
						"type" => "switch"
						),
					"sidebar_widgets_{$cpt}" => array(
						"title" => esc_html__('Sidebar widgets', 'freightco'),
						// Translators: Add CPT name to the description
						"desc" => wp_kses_data( sprintf(__('Select sidebar to show on the %s pages', 'freightco'), $title) ),
						"dependency" => array(
							"sidebar_position_{$cpt}" => array('left', 'right')
						),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
					"hide_sidebar_on_single_{$cpt}" => array(
						"title" => esc_html__('Hide sidebar on the single pages', 'freightco'),
						"desc" => wp_kses_data( __("Hide sidebar on the single page", 'freightco') ),
						"std" => 'inherit',
						"options" => array(
							'inherit' => esc_html__('Inherit', 'freightco'),
							1 => esc_html__('Hide', 'freightco'),
							0 => esc_html__('Show', 'freightco'),
						),
						"type" => "switch"
						),
						
					"footer_info_{$cpt}" => array(
						"title" => esc_html__('Footer', 'freightco'),
						"desc" => '',
						"type" => "info",
						),
					"footer_type_{$cpt}" => array(
						"title" => esc_html__('Footer style', 'freightco'),
						"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'freightco') ),
						"std" => 'inherit',
						"options" => freightco_get_list_header_footer_types(true),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "switch"
						),
					"footer_style_{$cpt}" => array(
						"title" => esc_html__('Select custom layout', 'freightco'),
						"desc" => wp_kses_data( __('Select custom layout to display the site footer', 'freightco') ),
						"std" => 'inherit',
						"dependency" => array(
							"footer_type_{$cpt}" => array('custom')
						),
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
						),
					"footer_widgets_{$cpt}" => array(
						"title" => esc_html__('Footer widgets', 'freightco'),
						"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'freightco') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default')
						),
						"std" => 'footer_widgets',
						"options" => array(),
						"type" => "select"
						),
					"footer_columns_{$cpt}" => array(
						"title" => esc_html__('Footer columns', 'freightco'),
						"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'freightco') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default'),
							"footer_widgets_{$cpt}" => array('^hide')
						),
						"std" => 0,
						"options" => freightco_get_list_range(0,6),
						"type" => "select"
						),
					"footer_wide_{$cpt}" => array(
						"title" => esc_html__('Footer fullwidth', 'freightco'),
						"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'freightco') ),
						"dependency" => array(
							"footer_type_{$cpt}" => array('default')
						),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"widgets_info_{$cpt}" => array(
						"title" => esc_html__('Additional panels', 'freightco'),
						"desc" => '',
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "info",
						),
					"widgets_above_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the top of the page', 'freightco'),
						"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'freightco') ),
						"std" => 'hide',
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
						),
					"widgets_above_content_{$cpt}" => array(
						"title" => esc_html__('Widgets above the content', 'freightco'),
						"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'freightco') ),
						"std" => 'hide',
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_content_{$cpt}" => array(
						"title" => esc_html__('Widgets below the content', 'freightco'),
						"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'freightco') ),
						"std" => 'hide',
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the bottom of the page', 'freightco'),
						"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'freightco') ),
						"std" => 'hide',
						"options" => array(),
						"type" => FREIGHTCO_THEME_FREE ? "hidden" : "select"
						)
					);
	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('freightco_options_get_list_choises')) {
	add_filter('freightco_filter_options_get_list_choises', 'freightco_options_get_list_choises', 10, 2);
	function freightco_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = freightco_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = freightco_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (strpos($id, '_scheme') > 0)
				$list = freightco_get_list_schemes($id!='color_scheme');
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = freightco_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = freightco_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = freightco_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = freightco_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = freightco_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = freightco_array_merge(array(0 => esc_html__('- Select category -', 'freightco')), freightco_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = freightco_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = freightco_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = freightco_get_list_load_fonts(true);
		}
		return $list;
	}
}
?>
