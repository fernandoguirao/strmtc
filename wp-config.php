<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lovster');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~V@dB)y!?}xM~M]:SOUH|*%ea3=<Y,4/+OK~]OLBA+M1X=a_{Il?[fR7u2<6.>-O');
define('SECURE_AUTH_KEY',  '$Q_xk}L1T]T3;*>t5%K|sk?c<+f!z#K6#ek~VuSQ:ch>tL%4D?.-[:/7?tT`pvx;');
define('LOGGED_IN_KEY',    'Wezj0@W .(}fSB}D}s4`C)0aLrMaTqz$Iv<T=znF#{;B$=v-!s#7jTt_(V/kp1a2');
define('NONCE_KEY',        'BcXB&luW-?-wwN7F?L,4]m7.Gi}<aHxpa@u3_a@*@{diZ~&4irc~.Z<ms1hp^4[A');
define('AUTH_SALT',        'PH/`~|q2MZUQl8b(5/{)3!@[/Pt+vHBajjh06[9__JFjFA2$#iy=;I(Pv6V9?aF ');
define('SECURE_AUTH_SALT', 'im_IU[}eX}&t]]ffJ`#&`Vzevh:2+h#lX,d^-AK0(dB2lVl}ZYZwL[-5SI%~js4c');
define('LOGGED_IN_SALT',   'mS33Fffb]glk6{D^:G=|tZ-c}.%zEPu /P[[U<!TKvee!Rs$%4jZ=E/>puP_{vn#');
define('NONCE_SALT',       'fWR&dky)lc-rw4#0j1^lxShEi$IqQ?HD) ~y?X&3{>-*KE7suKatKHB7+C1zef>h');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
