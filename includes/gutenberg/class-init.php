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

		/**
		 * Enqueue Gutenberg block assets for backend editor.
		 *
		 * `wp-blocks`: includes block type registration and related functions.
		 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
		 * `wp-i18n`: To internationalize the block's text.
		 *
		 * @since 1.0.0
		 */

		function block_assets() { // phpcs:ignore

			//get out quickly if no Gutenberg
			if ( !function_exists( 'register_block_type' ) ) {
				return;
			}

			// Register block styles for both frontend + backend.
			wp_register_style(
				'foopeople-block-style-css', // Handle.
				FOOPEOPLE_URL . '/assets/css/foopeople.blocks.min.css', // Block style CSS.
				is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
				null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
			);



			// Register block editor script for backend.
			wp_register_script(
				'foopeople-block-listing-js', // Handle.
				FOOPEOPLE_URL . '/assets/js/block-listing.min.js', // Block.build.js: We register the block here. Built with Webpack.
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
				null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
				true // Enqueue the script in the footer.
			);
			wp_register_script(
				'foopeople-block-single-js', // Handle.
				FOOPEOPLE_URL . '/assets/js/block-single.min.js', // Block.build.js: We register the block here. Built with Webpack.
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
				null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
				true // Enqueue the script in the footer.
			);
			wp_register_script(
				'foopeople-block-organogram-js', // Handle.
				FOOPEOPLE_URL . '/assets/js/block-organogram.min.js', // Block.build.js: We register the block here. Built with Webpack.
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



			// Register Front End Script
			wp_enqueue_script( 'foopeople_front_scripts', FOOPEOPLE_URL . '/assets/js/theme.min.js', array( 'jquery' ), FOOPEOPLE_VERSION, true );



			// WP Localized globals. Use dynamic PHP stuff in JavaScript via `foopeople` object.
			wp_localize_script(
				'foopeople-block-single-js',
				'foopeople-single', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access from `foopeople` object.
				]
			);
			wp_localize_script(
				'foopeople-block-listing-js',
				'foopeople-listing', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access from `foopeople` object.
				]
			);
			wp_localize_script(
				'foopeople-block-organogram-js',
				'foopeople-organogram', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access from `foopeople` object.
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
				'fooplugins/foopeople-single', array(
					'render_callback' => array( $this, 'render_block_single' ),
					'attributes' => array(
						'id' => array(
							'type' => 'number',
							'default' => 0
						),
						'className' => array(
							'type' => 'string'
						),
						'person' => array(
							'type' => 'string',
							'default' => ''
						),
						'person_id' => array(
							'type' => 'number',
							'default' => 98
						),
					),

					// Enqueue on both frontend & backend.
					'style'         => 'foopeople-block-style-css',
					// Enqueue in the editor only.
					'editor_script' => 'foopeople-block-single-js',
					// Enqueue in the editor only.
					'editor_style'  => 'foopeople-block-editor-css',
				)
			);

			register_block_type(
				'fooplugins/foopeople-listing', array(
					'render_callback' => array( $this, 'render_block_listing' ),
					'attributes' => array(
						'id' => array(
							'type' => 'number',
							'default' => 0
						),
						'className' => array(
							'type' => 'string'
						),
						'team' => array(
							'type' => 'string',
							'default' => ''
						),
						'team_id' => array(
							'type' => 'number'
						),
						'show_search' => array(
							'type' => 'boolean',
							'default' => true
						),
					),

					// Enqueue on both frontend & backend.
					'style'         => 'foopeople-block-style-css',
					// Enqueue in the editor only.
					'editor_script' => 'foopeople-block-listing-js',
					// Enqueue in the editor only.
					'editor_style'  => 'foopeople-block-editor-css',
				)
			);

			register_block_type(
				'fooplugins/foopeople-organogram', array(
					'render_callback' => array( $this, 'render_block_organogram' ),
					'attributes' => array(
						'id' => array(
							'type' => 'number',
							'default' => 0
						),
						'className' => array(
							'type' => 'string'
						)
					),

					// Enqueue on both frontend & backend.
					'style'         => 'foopeople-block-style-css',
					// Enqueue in the editor only.
					'editor_script' => 'foopeople-block-organogram-js',
					// Enqueue in the editor only.
					'editor_style'  => 'foopeople-block-editor-css',
				)
			);

		}

		/**
		 * Render the contents of the person block
		 *
		 * @param $attributes
		 *
		 * @return false|string|null
		 */
		function render_block_single( $attributes, $content ) {
			// var_dump($attributes);
			$output_string = foopeople_render_template('', 'person-single', false, $attributes );
			return !empty($output_string) ? $output_string : null;
		}

		/**
		 * Render the contents of the people block
		 *
		 * @param $attributes
		 *
		 * @return false|string|null
		 */
		function render_block_listing( $attributes, $content ) {
			// var_dump($attributes);
			$output_string = foopeople_render_template('', 'person-listing', false, $attributes );
			return !empty($output_string) ? $output_string : null;
		}

		/**
		 * Render the contents of the organogram
		 *
		 * @param $attributes
		 *
		 * @return false|string|null
		 */
		function render_block_organogram( $attributes, $content ) {

			$output_string = foopeople_render_template('', 'people-organogram', false, $attributes );
			return !empty($output_string) ? $output_string : null;
		}
	}
}