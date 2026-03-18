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
define( 'AUTH_KEY',          'DS_m*>+6WyTq4aySno8H`Mxju76^5OCX&B^XmJyu7WY)H1Rj5)unE#2hl3bWdH7{' );
define( 'SECURE_AUTH_KEY',   'Q05=t|<+=G-#L9u9kLWgIm[k-Vik*N#NU~DW[LSWFpC6 f5-o1;U&:g[%/ ~1P_E' );
define( 'LOGGED_IN_KEY',     '@mGn%Rdum(_4WYOQDlQ35:#v<|oXhc?NxTY6mRq0{YktCbY-9RlA.y/+1g1z|+O ' );
define( 'NONCE_KEY',         'lT,v,xRQg];ea3Js)37M*UaM#*WhCqS9r[`8?:yQmA{Cz3o6(mj.J;<<N~a=<l :' );
define( 'AUTH_SALT',         '<Imy{gt[VxoKJ?}e[o(f%C!OkR<VGhfr{Pei{`!@NRVpfUODb, `[CM6bgZ-K~@%' );
define( 'SECURE_AUTH_SALT',  '[[`Rm.t*IpKZ2hcHspVt4hg>B9n}cO(-7vVctu!o.V6Ir.D;wi<Pw)rjjps<^atD' );
define( 'LOGGED_IN_SALT',    'm3T6[;+A(l;)`Xp)b$?R:g.enynj!^E6cL%x&)iP*Xg<#sFiRR2~{)?Hf~wnLi@P' );
define( 'NONCE_SALT',        'oyx8>`)2;YU6/po-ORZzE.X2mXKH%$^pb}~~1hc8Nx`lLxc.K?/W@7bqNO:vW#jB' );
define( 'WP_CACHE_KEY_SALT', '4v=lW r{!^;#1345G)aaaNWrIzGK4cnJ45* Cn7|N6+70+.T+O.]<&d>[9+]f)&p' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

/* ── Security Hardening ── */
define( 'DISALLOW_FILE_EDIT', true );   // No theme/plugin editor in admin.
define( 'WP_POST_REVISIONS', 5 );       // Limit revisions to save DB space.
define( 'AUTOSAVE_INTERVAL', 120 );     // Autosave every 2 minutes.
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
