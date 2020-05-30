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
		}

		function register() {
			//allow extensions to override the people post type
			$args = apply_filters( 'FooPlugins\FooPeople\PostTypes\Person\RegisterArgs',
				array(
					'labels'        => array(
						'name'               => __( 'People', FOOPEOPLE_SLUG ),
						'singular_name'      => __( 'Person', FOOPEOPLE_SLUG ),
						'add_new'            => __( 'Add Person', FOOPEOPLE_SLUG ),
						'add_new_item'       => __( 'Add New Person', FOOPEOPLE_SLUG ),
						'edit_item'          => __( 'Edit Person', FOOPEOPLE_SLUG ),
						'new_item'           => __( 'New People', FOOPEOPLE_SLUG ),
						'view_item'          => __( 'View People', FOOPEOPLE_SLUG ),
						'search_items'       => __( 'Search People', FOOPEOPLE_SLUG ),
						'not_found'          => __( 'Nobody found', FOOPEOPLE_SLUG ),
						'not_found_in_trash' => __( 'Nobody found in Trash', FOOPEOPLE_SLUG ),
						'menu_name'          => __( 'People', FOOPEOPLE_SLUG ),
						'all_items'          => __( 'People', FOOPEOPLE_SLUG ),
						'featured_image'        => __( 'Portrait', FOOPEOPLE_SLUG ),
						'set_featured_image'    => __( 'Set Portrait', FOOPEOPLE_SLUG ),
						'remove_featured_image' => _x( 'Remove Portrait', FOOPEOPLE_SLUG ),
						'use_featured_image'    => _x( 'Use as Portrait', FOOPEOPLE_SLUG ),
					),
					'hierarchical'  => true,
					'public'        => false,
					'show_ui'       => true,
					'show_in_menu'  => true,
					'menu_icon'     => 'dashicons-groups',
					'supports'      => array( 'thumbnail', 'title' ),
				)
			);

			register_post_type( FOOPEOPLE_CPT_PERSON, $args );
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
					1  => __( 'Person updated.', FOOPEOPLE_SLUG ),
					2  => __( 'Person custom field updated.', FOOPEOPLE_SLUG ),
					3  => __( 'Person custom field deleted.', FOOPEOPLE_SLUG ),
					4  => __( 'Person updated.', FOOPEOPLE_SLUG ),
					5  => isset($_GET['revision']) ? sprintf( __( 'Person restored to revision from %s.', FOOPEOPLE_SLUG ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6  => __( 'Person published.', FOOPEOPLE_SLUG ),
					7  => __( 'Person saved.', FOOPEOPLE_SLUG ),
					8  => __( 'Person submitted.', FOOPEOPLE_SLUG ),
					9  => sprintf( __( 'People scheduled for: <strong>%1$s</strong>.', FOOPEOPLE_SLUG ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
					10 => __( 'Person draft updated.', FOOPEOPLE_SLUG )
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
					'updated'   => _n( '%s Person updated.', '%s People updated.', $bulk_counts['updated'], FOOPEOPLE_SLUG ),
					'locked'    => _n( '%s Person not updated, somebody is editing it.', '%s People not updated, somebody is editing them.', $bulk_counts['locked'], FOOPEOPLE_SLUG ),
					'deleted'   => _n( '%s Person permanently deleted.', '%s People permanently deleted.', $bulk_counts['deleted'], FOOPEOPLE_SLUG ),
					'trashed'   => _n( '%s Person moved to the Trash.', '%s People moved to the Trash.', $bulk_counts['trashed'], FOOPEOPLE_SLUG ),
					'untrashed' => _n( '%s Person restored from the Trash.', '%s People restored from the Trash.', $bulk_counts['untrashed'], FOOPEOPLE_SLUG ),
				)
			);

			return $bulk_messages;
		}
	}
}
