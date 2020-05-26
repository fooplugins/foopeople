<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * FooPeople Admin Init Class
 * Runs all classes that need to run in the admin
 */

if ( !class_exists( 'FooPlugins\FooPeople\Admin\Init' ) ) {

	class Init {

		/**
		 * Init constructor.
		 */
		function __construct() {
			new namespace\Updates();
			new namespace\Settings();
		}
	}
}
