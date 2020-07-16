<?php

namespace FooPlugins\FooPeople\Admin\Metaboxes;

use WP_User;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetaboxFieldGroup' ) ) {

	abstract class CustomPostTypeMetaboxFieldGroup extends CustomPostTypeMetabox {

		private $field_group;

		function __construct( $metabox, $field_group ) {
			$this->field_group = $field_group;
			$metabox['metabox_render_function'] = array( $this, 'render_metabox' );

			parent::__construct( $metabox );

			$hook = $this->metabox_hook_prefix();

			add_action( $hook . 'Render', array( $this, 'render_field_group_metabox' ), 10, 1 );

			//save field group post data
			add_action( $hook . 'Save', array( $this, 'save_field_group_data' ) );

			//enqueue assets needed for field groups
			add_action( $hook . 'EnqueueAssets', array( $this, 'enqueue_field_group_assets' ) );

			//handle ajax auto suggest fields
			add_action( 'wp_ajax_foometafield_suggest', array( $this, 'ajax_handle_autosuggest' ) );

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
		 * Render the metabox contents
		 *
		 * @param $post
		 */
		public function render_field_group_metabox( $post ) {
			$full_id = $this->metabox_id();

			//get the state from the post meta
			$state = get_post_meta( $post->ID, $this->metabox['meta_key'], true );

			//render the tab field group
			FieldRenderer::render_field_group( $this->field_group, $full_id, $state );
		}

		/**
		 * Save all the data in the field group
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function save_field_group_data( $post_id ) {
			$action = $this->metabox_hook_prefix();

			do_action( $action . 'PreSave', $post_id );

			//build up the state
			$state = $this->get_posted_data( $post_id );

			update_post_meta( $post_id, $this->metabox['meta_key'], $state );

			do_action( $action . 'PostSave', $post_id, $_POST, $state );
		}

		/**
		 * Get the sanitized posted data for the metabox
		 *
		 * @param $post_id
		 *
		 * @return mixed|void
		 */
		private function get_posted_data( $post_id ) {
			$full_id = $this->metabox_id();

			$sanitized_data = foopeople_safe_get_from_post( $full_id, array(), false );

			$data = $this->get_posted_data_recursive( $this->field_group , $sanitized_data );

			$filter = 'FooPlugins\FooPeople\Admin\Metaboxes\\' . $this->metabox['post_type'] . '\\' . $this->metabox['metabox_id'] . '\GetPostedData';

			$data = apply_filters( $filter, $data, $this, $post_id );

			return $data;
		}

		/**
		 * Recursively extract data from a sanitized data source
		 *
		 * @param $source
		 * @param $sanitized_data
		 *
		 * @return array
		 */
		function get_posted_data_recursive( $source, $sanitized_data ) {
			$data = array();

			//first, check if we have fields
			if ( isset( $source['fields'] ) ) {
				foreach ( $source['fields'] as $field ) {
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
						} else if ( 'repeater' === $type ) {
							$value = $this->get_posted_data_for_repeater( foopeople_clean( $value ) );
						} else {
							$value = foopeople_clean( $value );
						}

						//we have no value set, so check required
						if ( empty( $value) ) {
							if ( isset( $field['required'] ) && $field['required'] ) {
								if ( !isset( $data[ '__errors' ] ) ) {
									$data[ '__errors' ] = array();
								}
								$data[ '__errors' ][ $field['id'] ] = array(
										'message' => sprintf( __('%s is required!'), $field['label'] )
								);
							}
						}

						$data[ $field['id'] ] = $value;
					}
				}
			}

			//then check if we have tabs and loop through the tabs
			if ( isset( $source['tabs'] ) ) {
				foreach ( $source['tabs'] as $tab ) {
					$tab_data = $this->get_posted_data_recursive( $tab, $sanitized_data );
					$data = array_merge_recursive( $data, $tab_data );
				}
			}

			return $data;
		}

		/**
		 *
		 * @param $sanitized_data
		 */
		function get_posted_data_for_repeater( $sanitized_data ) {
			$results = array();
			foreach ( array_keys( $sanitized_data ) as $fieldKey ) {
				foreach ( $sanitized_data[$fieldKey] as $key => $value ) {
					$results[$key][$fieldKey] = $value;
				}
			}

			$current_username = 'unknown';
			$current_user = wp_get_current_user();
			if ( $current_user instanceof WP_User ) {
				$current_username = $current_user->user_login;
			}

			// stored some extra info for each row
			// check if each row has an __id field,
			//   if not then add one, so we can figure out which row to delete later.
			//   Also add a __created_by field and set to currently logged on user.
			//   And also a __created field which is the UTC timestamp of when the field was created
			// if the __id field exists, then we doing an update.
			//   update the __updated_by field and __updated timestamp fields
			foreach ( $results as &$result ) {
				if ( !isset($result['__id'] ) ) {
					$result['__id'] = wp_generate_password( 10, false, false );
					$result['__created'] = time();
					$result['__created_by'] = $current_username;
				} else {
					$result['__updated'] = time();
					$result['__updated_by'] = $current_username;
				}
			}

			return $results;
		}

		/***
		 * Enqueue the assets needed by the field groups
		 */
		function enqueue_field_group_assets() {
			wp_enqueue_script( 'selectize', $this->metabox['plugin_url'] . 'assets/js/selectize.min.js', array('jquery'), $this->metabox['plugin_version'] );
			wp_enqueue_script( 'foometafields', $this->metabox['plugin_url'] . 'assets/js/foometafields.min.js', array(
				'jquery',
				'suggest',
				'wp-color-picker',
				'selectize'
			), $this->metabox['plugin_version'] );
			wp_enqueue_style( 'foometafields', $this->metabox['plugin_url'] . 'assets/css/foometafields.min.css', array(
				'wp-color-picker'
			), $this->metabox['plugin_version'] );
		}
	}
}