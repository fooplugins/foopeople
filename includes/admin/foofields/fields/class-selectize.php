<?php

namespace FooPlugins\FooPeople\Admin\FooFields\Fields;

if ( ! class_exists( __NAMESPACE__ . '\Selectize' ) ) {

	class Selectize extends Field {

		protected $query;

		function __construct( $container, $type, $field_config ) {
			parent::__construct( $container, $type, $field_config );

			$this->query = isset( $field_config['query'] ) ? $field_config['query'] : false;

			//handle ajax selectize fields
			add_action( $this->field_ajax_action_name(), array( $this, 'ajax_handle_selectize' ) );
		}

		/**
		 * Override the data attributes
		 * @return array
		 */
		function data_attributes() {
			$data_attributes = parent::data_attributes();
			$data_attributes['data-query'] = build_query( array(
				'action'     => $this->field_action_name(),
				'nonce'      => $this->create_nonce()
			) );
			if ( isset( $this->placeholder ) ) {
				$data_attributes['data-selectize'] = array( 'placeholder' => $this->placeholder );
			}
			return $data_attributes;
		}

		/**
		 * Render the selectize field
		 *
		 * @param $optional_attributes
		 */
		function render_input( $override_attributes = false ) {

			$value = $this->value();

			if ( !is_array( $value ) ) {
				$value = array(
					'value'   => '',
					'display' => ''
				);
			}

			self::render_html_tag( 'input', array(
				'type'  => 'hidden',
				'id'    => $this->unique_id . '_display',
				'name'  => $this->name . '[display]',
				'value' => $value['display']
			) );

			$inner = '';

			if ( isset( $value['value'] ) ) {
				$inner = '<option value="' . esc_attr( $value['value'] ) . '" selected="selected">' . esc_html( $value['display'] ) . '</option>';
			}

			self::render_html_tag( 'select', array(
				'id'          => $this->unique_id,
				'name'        => $this->name . '[value]',
				'value'       => $value['value']
			), $inner, true, false );
		}

		/**
		 * Ajax handler for selectize fields
		 */
		function ajax_handle_selectize() {
			if ( $this->verify_nonce() ) {
				$s = trim( sanitize_key( $_REQUEST['q'] ) );
				if ( !empty( $s ) ) {
					$results = array();

					$this->container->do_action( 'selectize_' . $this->unique_id, $s, $this );

					if ( is_array( $this->query ) ) {
						$query_type = $this->query['type'];
						$query_data = isset( $this->query['data'] ) ? $this->query['data'] : null;

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
									'id'   => $post->ID,
									'text' => $post->post_title
								);
							}

						} else if ( 'taxonomy' === $query_type ) {

							$terms = get_terms(
								array(
									'search'     => $s,
									'taxonomy'   => $query_data,
									'hide_empty' => false
								)
							);

							foreach ( $terms as $term ) {
								$results[] = array(
									'id'   => $term->term_id,
									'text' => $term->name
								);
							}
						} else if ( 'user' === $query_type ) {

							$users = new \WP_User_Query( array(
								'search'         => '*'.esc_attr( $s ).'*',
								'search_columns' => array(
									'user_login',
									'user_nicename',
									'user_email',
									'user_url',
								),
							) );

							foreach ( $users->get_results() as $user ) {
								$results[] = array(
									'id'   => $user->ID,
									'text' => $user->display_name
								);
							}
						}

						wp_send_json( array(
							'results' => $results
						) );

						return;
					}
				}
			}
		}
	}
}
