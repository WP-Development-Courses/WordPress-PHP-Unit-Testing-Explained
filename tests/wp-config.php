<?php

/*
 * Path to the WordPress codebase you'd like to test.
 *
 * Add a forward slash in the end.
 */
define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/vendor/wordpress/' );

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
define( 'WP_DEFAULT_THEME', 'default' );

// Test with WordPress debug mode.
define( 'WP_DEBUG', true );

/*
 * Database settings.
 */
define( 'DB_NAME'       , getenv( 'WP_DB_NAME' ) ?: 'byline_tests' );
define( 'DB_USER'       , getenv( 'WP_DB_USER' ) ?: 'root' );
define( 'DB_PASSWORD'   , getenv( 'WP_DB_PASS' ) ?: '' );
define( 'DB_HOST'       , 'localhost' );
define( 'DB_CHARSET'    , 'utf8' );
define( 'DB_COLLATE'    , '' );

/*
 * Authentication Unique Keys and Salts.
 *
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

$table_prefix = 'tests_';   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WPLANG', '' );
