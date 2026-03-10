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
define( 'AUTH_KEY',          '%?iA]7UVbCX%!%0C[ARXo1GstJ^y3=bA(ob$m6AsXP]HRrTu0mD=S Eb;rqpz*I0' );
define( 'SECURE_AUTH_KEY',   'g^t7E;`~=OuJayRy2H{%!O;# ~3{HYe,084`YSX.D5e=HT.2}R4V7l!oe%EyQVyp' );
define( 'LOGGED_IN_KEY',     '$i~Vu%IU&Z*eQyQ8E$fiJT#ol(vTL,h;1-M/ZudTjVtE4}mOU?G9JP(%%c%Q0dhX' );
define( 'NONCE_KEY',         'X*-u J|!L84%cCJ95]sP8?<$ym 6 ,q>Ci(hxOY+lDIseK*Xrd8NFx^BAqGnp{Pb' );
define( 'AUTH_SALT',         '~8<~cW3]R1K{:jPd%M_;(|96Wu;TCE?xl@3)P8NV{p=]G5~MF&R6vT4`&xazv`#)' );
define( 'SECURE_AUTH_SALT',  '..XW_5Ac..K~WeE8];{!CY-NR3x.n:j+n)5R!tx4M9*]x~w-g?ga>vKG]rJoLv!_' );
define( 'LOGGED_IN_SALT',    'potVNAg{D@P:jB%z$>IJ-RrC`M.~:,(r17/ [|*|}Y~o8;4PCjgu@x2Brh:lHI2y' );
define( 'NONCE_SALT',        'H?fI{a3%V${TgR[9z2jKh /?Ie@Hz3dVbOSbupm+vUp..l$k-!i&;U}wjZgG/nJ8' );
define( 'WP_CACHE_KEY_SALT', 'L&kn@I2c>pem3nMW#qYYN39{Qh*nx XpA#s9><EX +]a>N$xrcTwvH%H!uCLB4xy' );


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
