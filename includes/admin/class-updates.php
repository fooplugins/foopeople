<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * FooPeople Admin Update Class
 * Checks if the plugin was updated and fires an action, so other code can hook into plugin updates
 */

if ( !class_exists( 'FooPlugins\FooPeople\Admin\Updates' ) ) {

	class Updates {

		/**
		 * Updates constructor.
		 */
		function __construct() {
			add_action( 'plugins_loaded', array( $this, 'do_checks' ) );
		}

		/**
		 * Run a check to see if we recently updated the plugin
		 */
		public function do_checks() {
			if ( false !== ( $update_data = get_site_transient( FOOPEOPLE_TRANSIENT_UPDATED ) ) ) {
				//clear the transient
				delete_site_transient( FOOPEOPLE_TRANSIENT_UPDATED );

				//fire an action!
				do_action( 'FooPlugins\FooPeople\Admin\Updated', $update_data );
			}
		}
	}
}

