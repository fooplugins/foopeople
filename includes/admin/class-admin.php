<?php
/*
 * PacePeople Admin class to store all general admin only logic
 */

if ( ! class_exists( 'PacePeople_Admin' ) ) {

	/**
	 * Class PacePeople_Admin
	 */
	class PacePeople_Admin {

		/**
		 *
		 */
		function __construct() {
			//init some other actions
			add_action( 'init', array( $this, 'init' ) );

			new PacePeople_Admin_Settings();
			new PacePeople_Admin_Menu();
			new PacePeople_Admin_Notices();

			new PacePeople_Person_MetaBoxes();
			new PacePeople_Person_Columns();

            new PacePeople_Admin_People_MetaBox_Preview();
		}

		function init() {
			add_filter( 'pacepeople_admin_has_settings_page', '__return_false' );
			add_action( 'pacepeople_admin_print_styles', array( $this, 'admin_print_styles' ) );
			add_action( 'pacepeople_admin_print_scripts', array( $this, 'admin_print_scripts' ) );
			// Add a links to the plugin listing
			add_filter( 'pacepeople_admin_plugin_action_links', array( $this, 'plugin_listing_links' ) );
		}


		function admin_print_styles() {
			$page       = safe_get_from_request( 'page' );
			$pacepeople = PacePeople_Plugin::get_instance();
			$pacepeople->register_and_enqueue_css( 'admin-page-' . $page . '.css' );
		}

		function admin_print_scripts() {
			$page       = safe_get_from_request( 'page' );
			$pacepeople = PacePeople_Plugin::get_instance();
			$pacepeople->register_and_enqueue_js( 'admin-page-' . $page . '.js' );
		}



		/**
		 * @param $links
		 *
		 * @return string
		 */
		function plugin_listing_links( $links ) {
			// Add a 'Settings' link to the plugin listing
			$links[] = '<a href="' . esc_url( pacepeople_admin_settings_url() ) . '"><b>' . __( 'Settings', FOOPEOPLE_SLUG ) . '</b></a>';

			$links[] = '<a href="' . esc_url( pacepeople_admin_help_url() ) . '"><b>' . __( 'Help', FOOPEOPLE_SLUG ) . '</b></a>';

			return $links;
		}
	}
}
