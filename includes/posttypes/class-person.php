<?php
namespace FooPlugins\FooPeople\PostTypes;

/*
 * FooPeople Person Custom Post Type
 */

if ( ! class_exists( 'FooPlugins\FooPeople\PostTypes\Person' ) ) {

	class Person {

		function __construct() {
			//register the post types
			add_action( 'init', array( $this, 'register' ) );

			//update post type messages
			add_filter( 'post_updated_messages', array( $this, 'update_messages' ) );

			//update post bulk messages
			add_filter( 'bulk_post_updated_messages', array( $this, 'update_bulk_messages' ), 10, 2 );

			// Add single person page template
			add_filter('single_template', array( $this,'load_single_person_template' ) );
		}

		function register() {
			//allow extensions to override the people post type
			$args = apply_filters( 'FooPlugins\FooPeople\PostTypes\Person\RegisterArgs',
				array(
					'labels'        => array(
						'name'               => __( 'People', 'foopeople' ),
						'singular_name'      => __( 'Person', 'foopeople' ),
						'add_new'            => __( 'Add Person', 'foopeople' ),
						'add_new_item'       => __( 'Add New Person', 'foopeople' ),
						'edit_item'          => __( 'Edit Person', 'foopeople' ),
						'new_item'           => __( 'New People', 'foopeople' ),
						'view_item'          => __( 'View People', 'foopeople' ),
						'search_items'       => __( 'Search People', 'foopeople' ),
						'not_found'          => __( 'Nobody found', 'foopeople' ),
						'not_found_in_trash' => __( 'Nobody found in Trash', 'foopeople' ),
						'menu_name'          => __( 'People', 'foopeople' ),
						'all_items'          => __( 'People', 'foopeople' ),
						'featured_image'        => __( 'Portrait', 'foopeople' ),
						'set_featured_image'    => __( 'Set Portrait', 'foopeople' ),
						'remove_featured_image' => _x( 'Remove Portrait', 'foopeople' ),
						'use_featured_image'    => _x( 'Use as Portrait', 'foopeople' ),
					),
					'hierarchical'  => true,
					'public'        => true,
					'show_ui'       => true,
					'show_in_menu'  => true,
					'menu_icon'     => 'dashicons-groups',
					'supports'      => array( 'thumbnail', 'title', 'comments' ),
				)
			);

			register_post_type( FOOPEOPLE_CPT_PERSON, $args );
		}


		/**
		 * Load the single person template
		 *
		 * @global object $post     The current post object.
		 *
		 * @param array   $template Which template we are using
		 *
		 * @return array $template Adjusted page template to use
		 */
		public function load_single_person_template($template) {
			global $post;
			/* Checks for single template by post type */
			if ( $post->post_type == FOOPEOPLE_CPT_PERSON ) {
				if ( file_exists( FOOPEOPLE_PATH.'includes/templates/person-single.php' ) ) {
					return FOOPEOPLE_PATH.'includes/templates/person-single.php';
				}
			}
			return $template;
		}

		/**
		 * Customize the update messages for a person
		 *
		 * @global object $post     The current post object.
		 *
		 * @param array   $messages Array of default post updated messages.
		 *
		 * @return array $messages Amended array of post updated messages.
		 */
		public function update_messages( $messages ) {

			global $post;

			// Add our people messages
			$messages[FOOPEOPLE_CPT_PERSON] = apply_filters( 'FooPlugins\FooPeople\PostTypes\Person\UpdateMessages',
				array(
					0  => '',
					1  => __( 'Person updated.', 'foopeople' ),
					2  => __( 'Person custom field updated.', 'foopeople' ),
					3  => __( 'Person custom field deleted.', 'foopeople' ),
					4  => __( 'Person updated.', 'foopeople' ),
					5  => isset($_GET['revision']) ? sprintf( __( 'Person restored to revision from %s.', 'foopeople' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6  => __( 'Person published.', 'foopeople' ),
					7  => __( 'Person saved.', 'foopeople' ),
					8  => __( 'Person submitted.', 'foopeople' ),
					9  => sprintf( __( 'People scheduled for: <strong>%1$s</strong>.', 'foopeople' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
					10 => __( 'Person draft updated.', 'foopeople' )
				)
			);

			return $messages;
		}

		/**
		 * Customize the bulk update messages for a people
		 *
		 * @param array $bulk_messages Array of default bulk updated messages.
		 * @param array $bulk_counts   Array containing count of posts involved in the action.
		 *
		 * @return array mixed            Amended array of bulk updated messages.
		 */
		function update_bulk_messages( $bulk_messages, $bulk_counts ) {

			$bulk_messages[FOOPEOPLE_CPT_PERSON] = apply_filters( 'FooPlugins\FooPeople\PostTypes\Person\BulkMessages',
				array(
					'updated'   => _n( '%s Person updated.', '%s People updated.', $bulk_counts['updated'], 'foopeople' ),
					'locked'    => _n( '%s Person not updated, somebody is editing it.', '%s People not updated, somebody is editing them.', $bulk_counts['locked'], 'foopeople' ),
					'deleted'   => _n( '%s Person permanently deleted.', '%s People permanently deleted.', $bulk_counts['deleted'], 'foopeople' ),
					'trashed'   => _n( '%s Person moved to the Trash.', '%s People moved to the Trash.', $bulk_counts['trashed'], 'foopeople' ),
					'untrashed' => _n( '%s Person restored from the Trash.', '%s People restored from the Trash.', $bulk_counts['untrashed'], 'foopeople' ),
				)
			);

			return $bulk_messages;
		}
	}
}
