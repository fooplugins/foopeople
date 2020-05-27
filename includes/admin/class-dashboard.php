<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * FooPeople Admin Dashboard Class
 */

if ( !class_exists( 'FooPlugins\FooPeople\Admin\Dashboard' ) ) {

	class Dashboard {
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
		}

		/**
		 * Add top level menu
		 */
		public function add_menu() {
			add_menu_page(
				__( 'FooPeople Dashboard' , 'foopeople' ),
				__( 'FooPeople' , 'foopeople' ),
				'manage_options',
				'foopeople',
				null,
				'dashicons-universal-access-alt'
			);

			add_submenu_page(
				'foopeople',
				__( 'FooPeople Dashboard' , 'foopeople' ),
				__( 'Dashboard' , 'foopeople' ),
				'manage_options',
				'foopeople',
				array( $this, 'render_dashboard_page' ),
				20
			);
		}

		/**
		 * Renders the contents for the settings page
		 */
		public function render_dashboard_page() {
			require_once FOOPEOPLE_PATH . 'includes/admin/views/dashboard.php';
		}
	}
}
