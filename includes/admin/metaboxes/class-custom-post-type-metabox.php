<?php

namespace FooPlugins\FooPeople\Admin\Metaboxes;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetabox' ) ) {

	abstract class CustomPostTypeMetabox {

		private $metabox;
		private $field_group;

		function __construct( $metabox, $field_group ) {
			$this->metabox     = $metabox;
			$this->field_group = $field_group;

			//add the metaboxes for a person
			add_action( 'add_meta_boxes_' . $metabox['post_type'], array( $this, 'add_meta_boxes' ) );

			//save extra post data for a person
			add_action( 'save_post', array( $this, 'save_post' ) );

			//enqueue assets needed for field groups
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			//handle ajax auto suggest fields
			add_action( 'wp_ajax_foometafield_suggest', array( $this, 'ajax_handle_autosuggest' ) );

			//handle ajax select2 fields
			add_action( 'wp_ajax_foometafield_select2', array( $this, 'ajax_handle_select2' ) );

			//handle ajax selectize fields
			add_action( 'wp_ajax_foometafield_selectize', array( $this, 'ajax_handle_selectize' ) );

		}

		/**
		 * Ajax handler for selectize fields
		 */
		function ajax_handle_selectize() {
			if ( wp_verify_nonce( foopeople_sanitize_key( 'nonce' ), 'foometafield_selectize' ) ) {
				$s     = foopeople_sanitize_text( 'q' );
				$s = trim( $s );

				$results = array();

				$query_type = foopeople_sanitize_key( 'query_type' );
				$query_data = foopeople_sanitize_key( 'query_data' );

				if ( 'post' === $query_type ) {

					$posts = get_posts(
							array(
									's'              => $s,
									'posts_per_page' => 5,
									'post_type'      => $query_data
							)
					);

					foreach ( $posts as $post ) {
						$results[] = array(
								'id' => $post->ID,
								'text' => $post->post_title
						);
					}

				} else if ( 'taxonomy' == $query_type ) {

					$terms = get_terms(
							array(
									'search'         => $s,
									'taxonomy'       => $query_data,
									'hide_empty'     => false
							)
					);

					foreach ( $terms as $term ) {
						$results[] = array(
								'id' => $term->ID,
								'text' => $term->name
						);
					}
				}

				wp_send_json( array(
						'results' => $results
				) );

				return;
			}

			wp_die();
		}

		/**
		 * Ajax handler for select2 fields
		 */
		function ajax_handle_select2() {
			if ( wp_verify_nonce( foopeople_sanitize_key( 'nonce' ), 'foometafield_select2' ) ) {
				$s     = foopeople_sanitize_text( 'q' );
				$s = trim( $s );

				$results = array();

				$query_type = foopeople_sanitize_key( 'query_type' );
				$query_data = foopeople_sanitize_key( 'query_data' );

				if ( 'post' === $query_type ) {

					$posts = get_posts(
							array(
									's'              => $s,
									'posts_per_page' => 5,
									'post_type'      => $query_data
							)
					);

					foreach ( $posts as $post ) {
						$results[] = array(
							'id' => $post->ID,
							'text' => $post->post_title
						);
					}

				} else if ( 'taxonomy' == $query_type ) {

					$terms = get_terms(
							array(
									'search'         => $s,
									'taxonomy'       => $query_data,
									'hide_empty'     => false
							)
					);

					foreach ( $terms as $term ) {
						$results[] = array(
							'id' => $term->ID,
							'text' => $term->name
						);
					}
				}

				wp_send_json( array(
					'results' => $results
				) );

				return;
			}

			wp_die();
		}

		/**
		 * Ajax handler for suggest fields
		 */
		function ajax_handle_autosuggest() {
			if ( wp_verify_nonce( foopeople_sanitize_key( 'nonce' ), 'foometafield_suggest' ) ) {
				$s     = foopeople_sanitize_text( 'q' );
				$comma = _x( ',', 'page delimiter' );
				if ( ',' !== $comma ) {
					$s = str_replace( $comma, ',', $s );
				}
				if ( false !== strpos( $s, ',' ) ) {
					$s = explode( ',', $s );
					$s = $s[ count( $s ) - 1 ];
				}
				$s = trim( $s );

				$results = array();

				$query_type = foopeople_sanitize_key( 'query_type' );
				$query_data = foopeople_sanitize_key( 'query_data' );

				if ( 'post' === $query_type ) {

					$posts = get_posts(
							array(
									's'              => $s,
									'posts_per_page' => 5,
									'post_type'      => $query_data
							)
					);

					foreach ( $posts as $post ) {
						$results[] = $post->post_title;
					}

				} else if ( 'taxonomy' == $query_type ) {

					$terms = get_terms(
							array(
									'search'         => $s,
									'taxonomy'       => $query_data,
									'hide_empty'     => false
							)
					);

					foreach ( $terms as $term ) {
						$results[] = $term->name;
					}
				}

				echo join( $results, "\n" );
			}

			wp_die();
		}

		/**
		 * Add metaboxe to the CPT
		 *
		 * @param $post
		 */
		function add_meta_boxes( $post ) {
			add_meta_box(
					$this->build_id(),
					$this->metabox['metabox_title'],
					array( $this, 'render_metabox' ),
					$this->metabox['post_type'],
					isset( $this->metabox['context'] ) ? $this->metabox['context'] : 'normal',
					isset( $this->metabox['priority'] ) ? $this->metabox['priority'] : 'high'
			);
		}

		/**
		 * Builds up an identifier from post_type and metabox_id
		 * @return string
		 */
		private function build_id() {
			return $this->metabox['post_type'] . '-' . $this->metabox['metabox_id'];
		}

		/**
		 * Render the metabox contents
		 *
		 * @param $post
		 */
		public function render_metabox( $post ) {
			$full_id = $this->build_id();

			//get the state from the post meta
			$state = get_post_meta( $post->ID, $this->metabox['meta_key'], true );

			//render the nonce used to validate when saving the metabox fields
			?><input type="hidden" name="<?php echo $full_id; ?>_nonce"
					 id="<?php echo $full_id; ?>_nonce"
					 value="<?php echo wp_create_nonce( $full_id ); ?>"/><?php

			//render the tab field group
			FieldRenderer::render_tabs( $this->field_group, $full_id, $state );
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

			$full_id = $this->build_id();

			// verify nonce
			if ( array_key_exists( $full_id . '_nonce', $_POST ) &&
				 wp_verify_nonce( $_POST[ $full_id . '_nonce' ], $full_id ) ) {

				//if we get here, we are dealing with the metabox fields

				$action = 'FooPlugins\FooPeople\Admin\Metaboxes\\' . $this->metabox['post_type'] . '\\' . $this->metabox['metabox_id'] . '\\';

				do_action( $action . 'PreSave', $post_id, $_POST );

				//build up the state
				$state = $this->get_posted_data( $post_id );

				update_post_meta( $post_id, $this->metabox['meta_key'], $state );

				// unhook this function so it doesn't loop infinitely
				remove_action( 'save_post', array( $this, 'save_post' ) );

				do_action( $action . 'PostSave', $post_id, $_POST, $state );

				// re-hook this function
				add_action( 'save_post', array( $this, 'save_post' ) );
			}
		}

		/**
		 * Get the sanitized posted data for the metabox
		 *
		 * @param $post_id
		 *
		 * @return mixed|void
		 */
		private function get_posted_data( $post_id ) {
			$full_id = $this->build_id();

			$sanitized_data = foopeople_safe_get_from_post( $full_id, array(), false );

			$data = array();

			//for some fields, we need to do special sanitization
			foreach ( $this->field_group['tabs'] as $tab ) {
				foreach ( $tab['fields'] as $field ) {

					if ( ! array_key_exists( $field['id'], $sanitized_data ) ) {
						//the field had no posted value, check for a default
						if ( isset( $field['default'] ) ) {
							$data[ $field['id'] ] = $field['default'];
						}
					} else {
						$value = $sanitized_data[ $field['id'] ];

						$type = sanitize_title( isset( $field['type'] ) ? $field['type'] : 'text' );

						//textareas need some special attention
						if ( 'textarea' === $type ) {
							$value = foopeople_sanitize_textarea( $value );
						} else {
							$value = foopeople_clean( $value );
						}

						$data[ $field['id'] ] = $value;
					}
				}
			}

			$filter = 'FooPlugins\FooPeople\Admin\Metaboxes\\' . $this->metabox['post_type'] . '\\' . $this->metabox['metabox_id'] . '\GetPostedData';

			$data = apply_filters( $filter, $data, $this, $post_id );

			return $data;
		}

		/***
		 * Enqueue the assets needed by the settings
		 *
		 * @param $hook_suffix
		 */
		function enqueue_assets( $hook_suffix ) {
			if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
				$screen = get_current_screen();

				if ( is_object( $screen ) && $this->metabox['post_type'] == $screen->post_type ) {
					// Register, enqueue scripts and styles here
					wp_enqueue_script( 'foometafields', $this->metabox['plugin_url'] . 'assets/js/foometafields.min.js', array(
							'jquery',
							'suggest',
							'wp-color-picker'
					), $this->metabox['plugin_version'] );
					wp_enqueue_style( 'foometafields', $this->metabox['plugin_url'] . 'assets/css/foometafields.min.css', array(
							'wp-color-picker'
					), $this->metabox['plugin_version'] );
				}
			}
		}
	}
}
