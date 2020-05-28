<?php
/*
 * PacePeople Admin Columns class
 */

if ( ! class_exists( 'PacePeople_Person_Columns' ) ) {

	class PacePeople_Person_Columns {

		public function __construct() {
			add_filter( 'manage_edit-' . PACEPEOPLE_CPT_PERSON . '_columns', array( $this, 'person_custom_columns' ) );
			//add_action( 'manage_' . PACEPEOPLE_CPT_PERSON . '_posts_custom_column', array( $this, 'person_custom_column_content' ), 10, 2 );
		}

		/**
		 * @param $columns
		 *
		 * @return array
		 */
		public function person_custom_columns( $columns ) {
			if ( array_key_exists( 'title', $columns ) ) {
				$columns['title'] = __( 'Full Name', 'pacepeople' );
			}

			if ( array_key_exists( 'date', $columns ) ) {
				$columns['date'] = __( 'Status', 'pacepeople' );
			}

			return $columns;
		}

		/**
		 * @param $column
		 * @param $post_id
		 */
		public function person_custom_column_content( $column, $post_id ) {
			global $post;

			switch ( $column ) {
				case PACEPEOPLE_CPT_PERSON . '_fullname':
					//$people = $this->get_local_people( $post );
					//echo $people->fullname;
					break;

			}
		}
	}
}
