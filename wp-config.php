<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'addweb_m1');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'PPDynEr*qumn+zhW=_Zl+j8fY`]9eeC<*xb3US;_.ZL2K13ui*FoKh<,53=.I$cI');
define('SECURE_AUTH_KEY',  'Lg{R(7u@-4!-,O:-j_#O[/icGrqivOl`S~BcplX}Z,yz*3npJyg2su+<J%1r;SVo');
define('LOGGED_IN_KEY',    '^G}*=:%W+3mT|Dt4w!8OLf#g8PAO{y%6IB<G&nLSn7oR.(FRz$vTh!MId:@+!vu{');
define('NONCE_KEY',        'r5C:H:%Zp(<pqkax)B8G|Vfi=}}tO/%:!5Q:BhdE+JXGw5($*T l|]]4Vg1$Z.[#');
define('AUTH_SALT',        'i3T~<dk<lQ;<O~Rh,I;<r#Hj23HWt^f;EAL|!f-ApxF6egk!epmh|W5gO1Pv)rnk');
define('SECURE_AUTH_SALT', '1g(cDpG*Fc9Yy3`5qh-8|=E[ohrEqKxpo:q?4XVQWI11IN-ljDo]^?+(@}V6FX4!');
define('LOGGED_IN_SALT',   '9{0(vm,1yj[7.EZ;ebj{}|)[:fcz%HepHy~=XvgR6bo%|,L+_pl~lALhypMJF.H:');
define('NONCE_SALT',       ':ViAC]_+|fyw40-sLtIR)[eovdg$`me/r{Ju=JYA(bL?y&d}+/wq|ki:=Y`:lzid');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'm1_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
