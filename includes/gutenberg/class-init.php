<?php
namespace FooPlugins\FooPeople\Gutenberg;

/**
 * FooPeople Gutenberg Functionality
 */


if ( ! class_exists( 'FooPlugins\FooPeople\Gutenberg\Init' ) ) {

	class Init {

		private $teams;

		function __construct() {
			$this->teams = json_encode( foopeople_get_taxonomies(FOOPEOPLE_CT_TEAM) );

			var_dump($this->teams);
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

			$asset_file = include( plugin_dir_path( __FILE__ ) . 'assets.php');

			//get out quickly if no Gutenberg
			if ( !function_exists( 'register_block_type' ) ) {
				return;
			}

			// Register block styles for both frontend + backend.
			wp_register_style(
				'foopeople-block-style-css',
				FOOPEOPLE_URL . '/assets/css/foopeople.blocks.min.css',
				is_admin() ? array( 'wp-editor' ) : null,
				FOOPEOPLE_VERSION
			);



			// Register block editor script for backend.
			wp_register_script(
				'foopeople-block-listing-js',
				FOOPEOPLE_URL . '/assets/js/block-listing.min.js',
				$asset_file['dependencies'],
				FOOPEOPLE_VERSION,
				true
			);
			wp_register_script(
				'foopeople-block-single-js',
				FOOPEOPLE_URL . '/assets/js/block-single.min.js',
				$asset_file['dependencies'],
				FOOPEOPLE_VERSION,
				true
			);
			wp_register_script(
				'foopeople-block-organogram-js',
				FOOPEOPLE_URL . '/assets/js/block-organogram.min.js',
				$asset_file['dependencies'],
				FOOPEOPLE_VERSION,
				true
			);


			// Register block editor styles for backend.
			wp_register_style(
				'foopeople-block-editor-css',
				FOOPEOPLE_URL . '/assets/css/foopeople.blocks.admin.min.css',
				array( 'wp-edit-blocks' ),
				FOOPEOPLE_VERSION
			);



			// Register Front End Script
			wp_enqueue_script( 'foopeople_front_scripts', FOOPEOPLE_URL . '/assets/js/theme.min.js', array( 'jquery' ), FOOPEOPLE_VERSION, true );



			// WP Localized globals. Use dynamic PHP stuff in JavaScript via `foopeople` object.
			wp_localize_script(
				'foopeople-block-single-js',
				'foopeopleSingle', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access
				]
			);

			wp_localize_script(
				'foopeople-block-listing-js',
				'foopeopleListing', // Array containing dynamic data for a JS Global.
				$this->teams
			);

			wp_localize_script(
				'foopeople-block-organogram-js',
				'foopeopleOrganogram', // Array containing dynamic data for a JS Global.
				[
					'pluginDirPath' => plugin_dir_path( __DIR__ ),
					'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
					// Add more data here that you want to access
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
						'team' => array(
							'type' => 'string',
							'default' => ''
						),
						'showSearch' => array(
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