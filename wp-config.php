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
define('REVISR_GIT_PATH', 'cmd/git'); // Added by Revisr
define('DB_NAME', 'appcom_wp595');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_HOME','http://localhost/gavkiwp');

define('WP_SITEURL','http://localhost/gavkiwp');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'zoclepi4its1lxhkno900mbrctdzqsezux3ccyu5jqxxts6d4hqxhs5g4r4udkvz');
define('SECURE_AUTH_KEY',  'ignm4e5yg9blsw7rtagfnbbuxsyffevpzcusteibqznnz0eqc8ydw6ryzhx2z3mx');
define('LOGGED_IN_KEY',    'iaaynfyhcjmumboynblmrgyoqz8ewdvh63khn6wl8pulik6fejvq7na94rgnwrqz');
define('NONCE_KEY',        'tuqsorrvl7aw82ol0m5ssy9vznyju5d4yzmku0ftfzw89fhvubbamkdeqikvty3m');
define('AUTH_SALT',        'lnmu0dl2pmvmjovbkqf855xvmym7fjz6pxfxogfm8lcr1rdvglhqmrtfmgloygpz');
define('SECURE_AUTH_SALT', 'wgvxon2fdx1vhppkfefe6gthhg1xr0o5r86xlipakvd1ba5m3kxzofwahsznxx0r');
define('LOGGED_IN_SALT',   'fwcnxs2sk4evgeuw2r6g4onbdp3ojcjuwjib2cydpwi05imwml5lstqzdjhixyi2');
define('NONCE_SALT',       'u5mcgzcaouoko1jirdxhe7giqzfvgp0tkwlnyqtdbgqipzx3rl1kh3w5pdrvdnxi');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpgo_';

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
