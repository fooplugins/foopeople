<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * FooPeople Admin Settings Class
 */

if ( !class_exists( 'FooPlugins\FooPeople\Admin\Settings' ) ) {

	class Settings {

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_stylesheet' ) );
		}

		/**
		 * Adds the admin stylesheet
		 */
		public function add_admin_stylesheet() {
			//enqueue any scripts or stylesheets you want to use on the settings page
		}

		/**
		 * Add menu to the tools menu
		 */
		public function add_menu() {
			add_submenu_page(
				'foopeople',
				__( 'FooPeople Settings', 'foopeople' ),
				__( 'Settings', 'foopeople' ),
				'manage_options',
				'foopeople-settings',
				array( $this, 'render_settings_page' )
			);
		}

		/**
		 * Renders the contents for the settings page
		 */
		public function render_settings_page() {
			require_once FOOPEOPLE_PATH . 'includes/admin/views/settings.php';
        }
	}
}
