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
define( 'DB_NAME', 'hbc' );

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
define( 'AUTH_KEY',         '^WtmlrK6+omdE8A]pW{;xrwtksL4[|0Chue,KyB#^FZ2a$%:`MXM&WiXgzAn}<BA' );
define( 'SECURE_AUTH_KEY',  'q>0#~d,Dwmm~J@F6jyBIKT+sdM4HJ7~]8cwUHtH99ZHtm,(uaZ6~65ppe 5f[?ZD' );
define( 'LOGGED_IN_KEY',    '/K;#fE%7R&Vv%!h@ao`q14F:B_>O?:aqh~{Fig+B/r*`i*KoB-,T^,EQq<12:cUt' );
define( 'NONCE_KEY',        'O^C.})`tgA80Z;~6nv#3/|XV-1H_#B?PD#ai4LkP44)9JNz]q3ZVKac_[CH%Y-{d' );
define( 'AUTH_SALT',        'Xh3T:RpTlxU1Re,Oif_Wk7DEH6+hN=gw:/l{hu1r<MfNB$S6CCOz[$!uTG(zC(x(' );
define( 'SECURE_AUTH_SALT', '9zFh^GV<}m(0,_*!Av~9gojO2q!bA ) W@*`b^ZdO`lD/L>:Zr[|`l-Dm?@xlMq5' );
define( 'LOGGED_IN_SALT',   '8^51/C<De<4;+cIO*Y(Y4Y@*3<-0>5RURr>$H3Jvw?&{o_7eAU.S`tE!RJKG(M]5' );
define( 'NONCE_SALT',       ')$8(O@5u|[GU~sAiS:FFwJ0^ZzE/`VgUib-w0F~>G5}B-ZGgG3w=jM@/ Q76S8pM' );

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
