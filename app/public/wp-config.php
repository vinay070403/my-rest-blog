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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '9{Q~ vss~MFGr736MgnpvE267GcTzO.Oijn!;Qb]>$|n+6Nm.,w<E} +Vw|l.~R5' );
define( 'SECURE_AUTH_KEY',   'Fm =9nBykb$g488^I-|ho7x?Y+;u|hJFZCP{7uW.?s8)v:^WC~n*rvf2iGLCd!sU' );
define( 'LOGGED_IN_KEY',     'I;k2raR0za?1b~:yyGH|@a|$Mf<s[WdATLoUuqiu7xg!1zu]&8mOBOhL5!Q:x$pk' );
define( 'NONCE_KEY',         '4Y93e8Z}(0h5h.?L|4YcxBsn-evX~;vOnx2UH~g&}>E.r7:%<LTA%Cn)e :7)k_S' );
define( 'AUTH_SALT',         ']8$zTJDyM;#@3RoYM&>MS!6H10(tLpneBc@=S,g]nuXJh0SDZ#zo2Pn6UCF{jHM.' );
define( 'SECURE_AUTH_SALT',  'qmV$J4h8#r+iIok&R)wcQ#0A}xhdE+ cdM1VTaN[#HFx}S17oY,GWWc!IxV{l=di' );
define( 'LOGGED_IN_SALT',    '4eUpllk+;z+<7n_F|hzEWG@85%lnrO,+[T;XVKhrQc G#A&*;IAmWl|A6%0KjOZm' );
define( 'NONCE_SALT',        'Xs_L6$lciRxvQNd+QE7r1cq<lw%eFxbP ;2vB&V|vys~r!~OpqfZ-.`}?^mfK`r2' );
define( 'WP_CACHE_KEY_SALT', 'z!$;M-IjOk0sitkQY0#|rDJbH@wwc^|t-M,Q(Z:g@!yKq?[z_%wVxvy]/2g|hy<0' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
