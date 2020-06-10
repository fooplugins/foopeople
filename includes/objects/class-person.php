<?php
namespace FooPlugins\FooPeople\Objects;

use stdClass;

/**
 * The main Person class
 */
if ( ! class_exists( 'FooPlugins\FooPeople\Objects\Person' ) ) {

	class Person extends stdClass {
		private $_post;

		/**
		 * constructor for a new instance
		 *
		 * @param $post
		 */
		function __construct($post = null) {
			$this->_post = null;
			$this->ID = 0;
			if ( isset( $post ) ) {
				$this->_post = $post;
				$this->ID = $post->ID;
				$this->slug = $post->post_name;
				$this->name = $post->post_title;
				$this->author = $post->post_author;
				$this->post_status = $post->post_status;
				$this->main_details = get_post_meta( $this->ID, FOOPEOPLE_META_PERSON_MAIN, true );
				$this->search = get_post_meta( $this->ID, FOOPEOPLE_META_PERSON_SEARCH, true );

				do_action( 'FooPlugins\FooPeople\Objects\Person\Loaded', $this );
			}
		}

		/**
		 * Returns the stored value for the item from main details
		 * @param $key
		 * @param mixed $default
		 *
		 * @return mixed
		 */
		public function get_main_detail( $key, $default = null ) {
			return foopeople_safe_get_from_array( $key, $this->main_details, $default );
		}

		/**
		 * Static function to load a Person instance by post id
		 *
		 * @param $post_id
		 *
		 * @return Person | boolean
		 */
		public static function get_by_id( $post_id ) {
			$post = get_post( $post_id );
			if ( $post ) {
				$person = new self( $post );
			} else {
				$person = false;
			}
			return $person;
		}

		/**
		 * Gets all teams for the person
		 *
		 * @return array
		 */
		public function teams() {
			$terms = get_the_terms( $this->ID, FOOPEOPLE_CT_TEAM );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$teams = array();

				foreach ($terms as $term) {
					$teams[] = $term->name;
				}

				return $teams;
			}
			return array();
		}

		/**
		 * Gets all locations for the person
		 *
		 * @return array
		 */
		public function locations() {
			$terms = get_the_terms( $this->ID, FOOPEOPLE_CT_LOCATION );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$locations = array();

				foreach ($terms as $term) {
					$locations[] = $term->name;
				}

				return $locations;
			}
			return array();
		}

		/**
		 * Gets all skills for the person

		 * @return array
		 */
		public function skills() {
			$terms = get_the_terms( $this->ID, FOOPEOPLE_CT_SKILL );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$skills = array();

				foreach ($terms as $term) {
					$skills[] = $term->name;
				}

				return $skills;
			}
			return array();
		}
	}
}
