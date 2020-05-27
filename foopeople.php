<?php
/*
Plugin Name: FooPeople
Description: People & Team Management for WordPress
Version:     1.0.0
Author:      Brad Vincent
Author URI:  https://fooplugins.com
Text Domain: foopeople
License:     GPL-2.0+
Domain Path: /languages

@fs_premium_only /includes/pro/

*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//define some FooPeople essentials
if ( !defined('FOOPEOPLE_SLUG' ) ) {
	define( 'FOOPEOPLE_SLUG', 'foopeople' );
	define( 'FOOPEOPLE_NAMESPACE', 'FooPlugins\FooPeople' );
	define( 'FOOPEOPLE_DIR', __DIR__ );
	define( 'FOOPEOPLE_PATH', plugin_dir_path( __FILE__ ) );
	define( 'FOOPEOPLE_URL', plugin_dir_url( __FILE__ ) );
	define( 'FOOPEOPLE_FILE', __FILE__ );
	define( 'FOOPEOPLE_VERSION', '1.0.0' );
	define( 'FOOPEOPLE_MIN_PHP', '5.4.0' ); // Minimum of PHP 5.4 required for autoloading, namespaces, etc
	define( 'FOOPEOPLE_MIN_WP', '4.4.0' );  // Minimum of WordPress 4.4 required
}

//include other essential FooPeople constants
require_once( FOOPEOPLE_PATH . 'includes/constants.php' );

//include common global FooPeople functions
require_once( FOOPEOPLE_PATH . 'includes/functions.php' );

//do a check to see if either free/pro version of the plugin is already running
if ( function_exists( 'foopeople_fs' ) ) {
	foopeople_fs()->set_basename( true, __FILE__ );
} else {
	if ( ! function_exists( 'foopeople_fs' ) ) {
		require_once( FOOPEOPLE_PATH . 'includes/freemius.php' );
	}

	//check minimum requirements before loading the plugin
	if ( require_once FOOPEOPLE_PATH . 'includes/startup-checks.php' ) {

		//start autoloader
		require_once( FOOPEOPLE_PATH . 'vendor/autoload.php' );

		spl_autoload_register( 'foopeople_autoloader' );

		//hook in activation
		register_activation_hook( __FILE__, array( 'FooPlugins\FooPeople\Activation', 'activate' ) );

		//start the plugin!
		new namespace\Init();
	}
}

