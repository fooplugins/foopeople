<?php
/**
 * Class for handling the person search index
 */

namespace FooPlugins\FooPeople\Admin\Person;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\SearchIndex' ) ) {

	class SearchIndex {
		function __construct() {

			$cpt = FOOPEOPLE_CPT_PERSON;
			add_action( "FooPlugins\FooPeople\Admin\Metaboxes\{$cpt}\details\PostSave", array( $this, 'save_search_index' ), 10, 3 );

			//load the search index for the person after it the person is loaded from the database.
			// TODO: Figure out if this is needed
			//add_action( 'FooPlugins\FooPeople\Person\Loaded', array( $this, 'load_search_index' ), 10, 1 );
		}

		/**
		 * Saves the person search index for later use
		 *
		 * @param $post_id
		 * @param $post
		 * @param $state
		 */
		public function save_search_index( $post_id, $post, $state ) {
			//get the person object from the database
			$person = FooPlugins\FooPeople\Person::get_by_id( $post_id );

			//build up the search index
			$search_index = $this->build_search_index( $person );

			//save the index to post meta
			update_post_meta( $post_id, FOOPEOPLE_META_PERSON_SEARCH, $search_index );
		}

		/**
		 * Loads the search index from post meta
		 *
		 * @param FooPlugins\FooPeople\Person $person
		 */
		public function load_search_index( $person ) {
			$person->search_index = get_post_meta( $person->ID, FOOPEOPLE_META_PERSON_SEARCH, true );
		}

		/**
		 * Builds up a search index for a Person
		 *
		 * @param FooPlugins\FooPeople\Person $person
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
			return strtolower( implode( ' ', $terms ) );
		}
	}
}
