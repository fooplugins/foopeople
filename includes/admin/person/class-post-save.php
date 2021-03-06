<?php
/**
 * Class for handling the PostSave action for a person
 */

namespace FooPlugins\FooPeople\Admin\Person;

use FooPlugins\FooPeople\Objects\Person;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\PostSave' ) ) {

	class PostSave {

		function __construct() {
			add_action( 'foopeople_admin_person_metaboxmaindetails_after_save_post_meta', array(
				$this,
				'post_save_person'
			), 10, 2 );

			add_filter( 'foopeople_admin_person_metaboxmaindetails_get_posted_data', array(
				$this,
				'validate_posted_data'
			), 10, 2 );
		}

		/**
		 * Do some validation on the person to make sure we do not set the manager to itself
		 *
		 * @param $data
		 * @param $metabox
		 *
		 * @return mixed
		 */
		public function validate_posted_data( $data, $metabox ) {
			//get the line manager and make sure it is not the same
			$manager_id = intval( foopeople_safe_get_from_array( 'value', foopeople_safe_get_from_array( 'manager', $data, array() ), 0 ) );

			$post_id = $metabox->get_post_id();

			if ( $post_id === $manager_id ) {
				//TODO : set an error message for the person
				unset( $data['manager'] );
			}

			return $data;
		}

		/**
		 * Performs all neccessary PostSave tasks for a person, including:
		 * - Updates the person search index
		 * - Updates the person parent relationship based on the line manager
		 *
		 * @param $post_id
		 * @param $state
		 */
		public function post_save_person( $post_id, $state ) {
			//get the person object from the database
			$person = Person::get_by_id( $post_id );

			$manager_id = $person->manager_id;

			//make sure we do not set the parent to itself
			if ( $post_id === $person->manager_id ) {
				$manager_id = 0;
			}

			//update the post_parent
			wp_update_post(
				array(
					'ID' => $post_id,
					'post_parent' => $person->manager_id
				)
			);

			$this->update_search_index( $person );
		}

		/**
		 * Updates the search index for a Person
		 *
		 * @param Person $person
		 */
		public function update_search_index( $person ) {
			//build up the search index
			$search_index = $this->build_search_index( $person );

			//save the index to post meta
			update_post_meta( $person->ID, FOOPEOPLE_META_PERSON_SEARCH, $search_index );
		}

		/**
		 * Builds up a search index for a Person
		 *
		 * @param Person $person
		 *
		 * @return string
		 */
		public function build_search_index( $person ) {
			$terms[] = $person->get_main_detail('firstname', '');
			$terms[] = $person->get_main_detail('surname', '');
			$terms[] = $person->get_main_detail('preferred', '');
			$terms[] = $person->get_main_detail('jobtitle', '');
			$terms[] = implode( ' ', $person->teams() );
			$terms[] = implode( ' ', $person->locations() );
			$terms[] = implode( ' ', $person->skills() );
			return strtolower( implode( ' ', array_filter( $terms, 'strlen' ) ) );
		}
	}
}
