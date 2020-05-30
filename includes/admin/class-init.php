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

			new namespace\Metaboxes\Person\MainDetails();
			new namespace\Person_Metabox_Preview();
		}

		function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'init_stylesheets_and_scripts' ) );
		}


		function init_stylesheets_and_scripts() {
			wp_register_style( 'foopeople_preview_styles', plugin_dir_url(dirname( __FILE__ )) . '../assets/css/foopeople.admin.min.css', array(), '' );
			wp_enqueue_style( 'foopeople_preview_styles' );

			wp_register_script( 'foopeople_preview_scripts', plugin_dir_url(dirname( __FILE__ )) . '../assets/js/admin.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'foopeople_preview_scripts' );
		}

	}
}
