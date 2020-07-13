<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * FooPeople Admin Init Class
 * Runs all classes that need to run in the admin
 */

if ( !class_exists( 'FooPlugins\FooPeople\Admin\Init' ) ) {

	class Init {

		/**
		 * constructor.
		 */
		function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

			new namespace\Updates();
			new namespace\Dashboard();
			// new namespace\Settings();

			new namespace\Person\PostSave();
			new namespace\Person\MetaboxMainDetails();
			new namespace\Person\MetaboxNotes();
			new namespace\Person\MetaboxPersonPreview();
		}

		function enqueue( $hook_suffix ) {
			if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
				$screen = get_current_screen();

				if ( is_object( $screen ) && FOOPEOPLE_CPT_PERSON == $screen->post_type ) {
					wp_enqueue_style( 'foopeople_preview_styles', FOOPEOPLE_URL . 'assets/css/foopeople.admin.min.css', array(), FOOPEOPLE_VERSION );
					wp_enqueue_script( 'foopeople_preview_scripts', FOOPEOPLE_URL . 'assets/js/admin.min.js', array( 'jquery' ), FOOPEOPLE_VERSION, true );
				}
			}
		}
	}
}
