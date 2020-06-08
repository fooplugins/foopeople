<?php
namespace FooPlugins\FooPeople\Gutenberg;

/**
 * FooPeople Gutenberg Functionality
 */


if ( ! class_exists( 'FooPlugins\FooPeople\Gutenberg\Init' ) ) {

	class Init {

		function __construct() {
			add_action( 'init',  array( $this, 'block_assets') );
		}


		function block_assets() { // phpcs:ignore
			// Register block styles for both frontend + backend.
			wp_register_style(
				'foopeople-block-style-css', // Handle.
				FOOPEOPLE_URL . '/assets/css/foopeople.blocks.min.css', // Block style CSS.
				is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
				null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
			);

			// Register block editor script for backend.
			wp_register_script(
				'foopeople-block-js', // Handle.
				FOOPEOPLE_URL . '/assets/js/blocks.min.js', // Block.build.js: We register the block here. Built with Webpack.
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
				null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
				true // Enqueue the script in the footer.
			);


			// Register block editor styles for backend.
			wp_register_style(
				'foopeople-block-editor-css', // Handle.
				FOOPEOPLE_URL . '/assets/css/foopeople.blocks.admin.min.css', // Block editor CSS.
				array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
				null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
			);

			// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
			wp_localize_script(
				'foopeople-block-js',
				'cgbGlobal', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access from `cgbGlobal` object.
				]
			);

			/**
			 * Register Gutenberg block on server-side.
			 *
			 * Register the block on server-side to ensure that the block
			 * scripts and styles for both frontend and backend are
			 * enqueued when the editor loads.
			 *
			 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
			 * @since 1.16.0
			 */
			register_block_type(
				'fooplugins/foopeople', array(
					// Enqueue on both frontend & backend.
					'style'         => 'foopeople-block-style-css',
					// Enqueue in the editor only.
					'editor_script' => 'foopeople-block-js',
					// Enqueue in the editor only.
					'editor_style'  => 'foopeople-block-editor-css',
				)
			);
		}



	}
}