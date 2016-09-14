<?php
//increase WP Memory Limit
define( 'WP_MEMORY_LIMIT', '256M' );
set_time_limit(420);

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
define('DB_NAME', 'i1989250_wp1');

/** MySQL database username */
define('DB_USER', 'i1989250_wp1');

/** MySQL database password */
define('DB_PASSWORD', 'Q~fcuf6jwd[z3Gt9hc]19.#8');

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
define('AUTH_KEY',         '9SiabMpI9VM5sDHXt9D1BZ8HMDqXPp6q4FQIuJewiGwWudhQzEqPeCtUwRfmduls');
define('SECURE_AUTH_KEY',  'qALXWpsU3V2iIi08yMVUCRx2Xardgh8fJUh6yYlY5h8H3YA4gLddYxYmzBMe3s8U');
define('LOGGED_IN_KEY',    '2TGmLDdoM7XAxCOAcf2poRyhI7Y1Qk2iviTABaLFpiZEdJYeZtDIxBs8ENv0oaix');
define('NONCE_KEY',        'ytBDPr4jqst4IOhCCWHo4uW1B6quWj9Dc1GsC4XQylcDlVeTh5p1Zed98BD6tsi2');
define('AUTH_SALT',        'lYzMJMCal8cQXMYoJgyDipycCvZQTNJI1IvfsFkcDUSj14NR9sMeXw5AXoK6xmwa');
define('SECURE_AUTH_SALT', '299B8ElFmOeKLuUlAt8RoapBSBDE8AaWGFlghkuCpk8vRGUPu3B7CAfOeYz6WDRH');
define('LOGGED_IN_SALT',   'B3K8FqZ8sv8hSXB9WptpIeGW5RIlrbzASrHgHiywcipi8UlqMZITIk1Urq29a1ro');
define('NONCE_SALT',       'zBU0lyEu46JOGNWKaDimyvcuIfHwbSk0XpnluQ11WhQzD8ZNXDnJv0LgpPea8qkb');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);

/**
 * Multi-site
 *
 */
define('MULTISITE', false);
define('SUBDOMAIN_INSTALL', false);
$base = '/blogs/';
define('DOMAIN_CURRENT_SITE', 'www.kajinonline.com');
define('PATH_CURRENT_SITE', '/blogs/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define('WP_ALLOW_MULTISITE', false);
define('DISALLOW_FILE_EDIT', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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