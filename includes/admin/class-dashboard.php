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

			// We dont need a seperate menu item, as we place all sub menus udner the Custom Post Type Menu
			// add_menu_page(
			// 	__( 'FooPeople Dashboard' , 'foopeople' ),
			// 	__( 'FooPeople' , 'foopeople' ),
			// 	'manage_options',
			// 	'foopeople',
			// 	null,
			// 	'dashicons-universal-access-alt'
			// );

			add_submenu_page(
				foopeople_admin_menu_cpt_slug(),
				__( 'FooPeople Dashboard' , 'foopeople' ),
				__( 'Dashboard' , 'foopeople' ),
				'manage_options',
				'foopeople',
				array( $this, 'render_dashboard_page' ),
				20
			);
		}

		/**
		 * Renders the contents for the dashboard page
		 */
		public function render_dashboard_page() {
			require_once FOOPEOPLE_PATH . 'includes/admin/views/dashboard.php';
		}
	}
}
