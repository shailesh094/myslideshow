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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpdemo' );

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
define( 'AUTH_KEY',         '.t6F/zLfc+h(5U5[^(efCqSLZy{^6/k6~~~)kW{mCj-i6v2e#671G.7HaHNg;YRf' );
define( 'SECURE_AUTH_KEY',  '^@V0yjRx@$G8w4ZwNYi@X@Gptp.Zr?AYEZrK/f_9b`b$0lC)fCW4wXn%VuoZhDB6' );
define( 'LOGGED_IN_KEY',    '%70MgpdRM=+Der8&FohD8<7}P}qAPJDM+50sD_:v5I}HmxkkdiD9{N)*]u6~uY=0' );
define( 'NONCE_KEY',        'Ey7]$j.Pu<BLd 2O=WI0n|R0Am|W@3eV#tD$B+7D xv_j5f,y:wPp,>H~U/^K~A+' );
define( 'AUTH_SALT',        '#2KV7eLTd>_}GP8#I`gU1(H@m}PSf&0<H,~]2NvV_u@k`>[CmLbnO%G1| ch+q.>' );
define( 'SECURE_AUTH_SALT', 'i)ra2B!%I-9)f=g88/S9vU8V2uEqQmmr6sR#8I3lrH dLiDRtJkCflI8e(Q!+3o>' );
define( 'LOGGED_IN_SALT',   't3ZW#e+W4(#z)~Eg:14TCgE||OYI7<@r$g5H.t;]_E=S%:u W6{~zFimb5F~boAT' );
define( 'NONCE_SALT',       '3NTk]^j@gtIJ3+]}i*8<v!(z/z}{#7RQWSJ2D.,@K3PT.co:ce;KsZ8|h0,rU~uq' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
