<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'firebox' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'm!rYH{?]onNP/RUDG5rAFG J9%)rJ;l(bZ!,fm{U$vXVX%z5BJq;*,E_5tA-%QUH' );
define( 'SECURE_AUTH_KEY',  'o>5VveuGx|6md_.;b)~#4Y9`N%H^@y,j5.^X_#)eZS|K/r($@/9KA4yaSq~iYU`B' );
define( 'LOGGED_IN_KEY',    'a!?/mAL`0CcORzmqGj&%X0S-5|rM|#wYZveQ$K0rz-_!pRj]$nxQ<2%oP2&jU,=}' );
define( 'NONCE_KEY',        'R!$p=p?n_EJ-J}%2w{_%o]GDO;8iKn~xi]lt#HoVvZB<fSB3?+0pR9K^egb0&4Wj' );
define( 'AUTH_SALT',        'cZL t0(s*0X{8)L!oZl<(3@#I5U`+k3G`iUW*qf{mhAFJB9*F-LM}y[ReMp7sZU.' );
define( 'SECURE_AUTH_SALT', 'mE=@-4GV;EQ{:EjVy/]0eq~B|5g>F/jFbWII5dTl}e Wr-+1D9NUln5dp o*LI4k' );
define( 'LOGGED_IN_SALT',   'Gl4Gr20 M(4OYaY3&9|I~eOwRw ?N$`u4^$Of<6*9wS dI=;W&)O=FLZuV5pD!tq' );
define( 'NONCE_SALT',       'K!<0e 1?-{X!Oz`N.XkOIrlrCbpR)ZAW,cFv1jIy$~FmrEol4e`l*P1!7!J-OT;{' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
