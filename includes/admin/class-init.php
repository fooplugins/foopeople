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
			add_action( 'init', array( $this, 'init' ) );

			new namespace\Updates();
			new namespace\Dashboard();
			new namespace\Settings();

			new namespace\Person();
			new namespace\Person_Metaboxes();
			new namespace\Person_Columns();
			new namespace\Person_Metabox_Preview();
		}

		function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'init_stylesheets_and_scripts' ) );
			// add_filter( 'pacepeople_admin_has_settings_page', '__return_false' );
			// add_action( 'pacepeople_admin_print_styles', array( $this, 'admin_print_styles' ) );
			// add_action( 'pacepeople_admin_print_scripts', array( $this, 'admin_print_scripts' ) );
			// Add a links to the plugin listing
			// add_filter( 'pacepeople_admin_plugin_action_links', array( $this, 'plugin_listing_links' ) );
		}


		function init_stylesheets_and_scripts() {
			wp_register_style( 'foopeople_preview_styles', plugin_dir_url(dirname( __FILE__ )) . '../assets/css/foopeople.admin.min.css', array(), '' );
			wp_enqueue_style( 'foopeople_preview_styles' );

			wp_register_script( 'foopeople_preview_scripts', plugin_dir_url(dirname( __FILE__ )) . '../assets/js/admin.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'foopeople_preview_scripts' );
		}

		// function admin_print_styles() {
		// 	$page       = safe_get_from_request( 'page' );
		// 	$foopeople = FOOPEOPLE_NAMESPACE::get_instance();
		// 	$foopeople->register_and_enqueue_css( 'admin-page-' . $page . '.css' );
		// }

		// function admin_print_scripts() {
		// 	$page       = safe_get_from_request( 'page' );
		// 	$foopeople = FOOPEOPLE_NAMESPACE::get_instance();
		// 	$foopeople->register_and_enqueue_js( 'admin-page-' . $page . '.js' );
		// }

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
