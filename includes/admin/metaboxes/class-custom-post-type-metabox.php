<?php

namespace FooPlugins\FooPeople\Admin\Metaboxes;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetabox' ) ) {

	abstract class CustomPostTypeMetabox {

		protected $metabox;

		function __construct( $metabox ) {
			$this->metabox = $metabox;

			//add the metaboxes for a person
			add_action( 'add_meta_boxes_' . $metabox['post_type'], array( $this, 'add_meta_boxes' ) );

			//save extra post data for a person
			add_action( 'save_post', array( $this, 'save_post' ) );

			//enqueue assets needed for this metabox
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		}

		/**
		 * Add metaboxe to the CPT
		 *
		 * @param $post
		 */
		function add_meta_boxes( $post ) {
			add_meta_box(
				$this->metabox_id(),
				$this->metabox['metabox_title'],
				$this->metabox['metabox_render_function'],
				$this->metabox['post_type'],
				isset( $this->metabox['context'] ) ? $this->metabox['context'] : 'normal',
				isset( $this->metabox['priority'] ) ? $this->metabox['priority'] : 'default'
			);
		}

		/**
		 * Builds up an identifier from post_type and metabox_id
		 * @return string
		 */
		protected function metabox_id() {
			return $this->metabox['post_type'] . '-' . $this->metabox['metabox_id'];
		}

		protected function metabox_hook_prefix() {
			return __NAMESPACE__ . '\\' . $this->metabox['post_type'] . '\\' . $this->metabox['metabox_id'] . '\\';
		}

		/**
		 * Render the metabox contents
		 *
		 * @param $post
		 */
		public function render_metabox( $post ) {
			$full_id = $this->metabox_id();

			//render the nonce used to validate when saving the metabox fields
			?><input type="hidden" name="<?php echo $full_id; ?>_nonce"
			         id="<?php echo $full_id; ?>_nonce"
			         value="<?php echo wp_create_nonce( $full_id ); ?>"/><?php

			do_action( $this->metabox_hook_prefix() . 'Render', $post );
		}

		/**
		 * Hook into the save post and save the fields
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function save_post( $post_id ) {
			// check autosave first
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			$full_id = $this->metabox_id();

			// verify nonce
			if ( array_key_exists( $full_id . '_nonce', $_POST ) &&
			     wp_verify_nonce( $_POST[ $full_id . '_nonce' ], $full_id ) ) {

				//if we get here, we are dealing with the correct metabox

				// unhook this function so it doesn't loop infinitely
				remove_action( 'save_post', array( $this, 'save_post' ) );

				do_action( $this->metabox_hook_prefix() . 'Save', $post_id );

				// re-hook this function
				add_action( 'save_post', array( $this, 'save_post' ) );
			}
		}

		/***
		 * Enqueue the assets needed by the metabox
		 *
		 * @param $hook_suffix
		 */
		function enqueue_assets( $hook_suffix ) {
			if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
				$screen = get_current_screen();

				if ( is_object( $screen ) && $this->metabox['post_type'] == $screen->post_type ) {
					// Register, enqueue scripts and styles here

					do_action( $this->metabox_hook_prefix() . 'EnqueueAssets' );

					if ( isset( $this->metabox['scripts'] ) ) {
						foreach ( $this->metabox['scripts'] as $script ) {
							wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], $script['ver'], isset( $script['in_footer'] ) ? $script['in_footer'] : false );
						}
					}

					if ( isset( $this->metabox['styles'] ) ) {
						foreach ( $this->metabox['styles'] as $style ) {
							wp_enqueue_style( $style['handle'], $style['src'], $style['deps'], $style['ver'], isset( $style['media'] ) ? $style['media'] : 'all' );
						}
					}
				}
			}
		}
	}
}
