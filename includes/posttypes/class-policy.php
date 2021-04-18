<?php
namespace FooPlugins\FooPeople\PostTypes;

/*
 * Policy Custom Post Type
 */

if ( ! class_exists( 'FooPlugins\FooPeople\PostTypes\Policy' ) ) {

	class Policy {

		function __construct() {
			//register the post types
			add_action( 'init', array( $this, 'register' ) );

			//update post type messages
			add_filter( 'post_updated_messages', array( $this, 'update_messages' ) );

			//update post bulk messages
			add_filter( 'bulk_post_updated_messages', array( $this, 'update_bulk_messages' ), 10, 2 );
		}

		function register() {
			//allow extensions to override the policy post type
			$args = apply_filters( 'FooPlugins\FooPeople\PostTypes\Policy\RegisterArgs',
				array(
					'labels'        => array(
						'name'               => __( 'Policies', 'foopeople' ),
						'singular_name'      => __( 'Policy', 'foopeople' ),
						'add_new'            => __( 'Add Policy', 'foopeople' ),
						'add_new_item'       => __( 'Add New Policy', 'foopeople' ),
						'edit_item'          => __( 'Edit Policy', 'foopeople' ),
						'new_item'           => __( 'New Policy', 'foopeople' ),
						'view_item'          => __( 'View Policies', 'foopeople' ),
						'search_items'       => __( 'Search Policies', 'foopeople' ),
						'not_found'          => __( 'No Policies found', 'foopeople' ),
						'not_found_in_trash' => __( 'No Policies found in Trash', 'foopeople' ),
						'menu_name'          => __( 'Policies', 'foopeople' ),
						'all_items'          => __( 'Policies', 'foopeople' )
					),
					'hierarchical'  => true,
					'public'        => false,
					'rewrite'       => false,
					'show_ui'       => true,
					'show_in_menu'  => true,
					'menu_icon'     => 'dashicons-media-text',
					'supports'      => array('title', 'editor', 'revisions', ),
				)
			);

			register_post_type( FOOPEOPLE_CPT_POLICY, $args );
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

			// Add our policy messages
			$messages[FOOPEOPLE_CPT_POLICY] = apply_filters( 'FooPlugins\FooPeople\PostTypes\Policy\UpdateMessages',
				array(
					0  => '',
					1  => __( 'Policy updated.', 'foopeople' ),
					2  => __( 'Policy custom field updated.', 'foopeople' ),
					3  => __( 'Policy custom field deleted.', 'foopeople' ),
					4  => __( 'Policy updated.', 'foopeople' ),
					5  => isset($_GET['revision']) ? sprintf( __( 'Policy restored to revision from %s.', 'foopeople' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6  => __( 'Policy published.', 'foopeople' ),
					7  => __( 'Policy saved.', 'foopeople' ),
					8  => __( 'Policy submitted.', 'foopeople' ),
					9  => sprintf( __( 'Policies scheduled for: <strong>%1$s</strong>.', 'foopeople' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
					10 => __( 'Policy draft updated.', 'foopeople' )
				)
			);

			return $messages;

		}

		/**
		 * Customize the bulk update messages for a policy
		 *
		 * @param array $bulk_messages Array of default bulk updated messages.
		 * @param array $bulk_counts   Array containing count of posts involved in the action.
		 *
		 * @return array mixed            Amended array of bulk updated messages.
		 */
		function update_bulk_messages( $bulk_messages, $bulk_counts ) {

			$bulk_messages[FOOPEOPLE_CPT_POLICY] = apply_filters( 'FooPlugins\FooPeople\PostTypes\Policy\BulkMessages',
				array(
					'updated'   => _n( '%s Policy updated.', '%s Policies updated.', $bulk_counts['updated'], 'foopeople' ),
					'locked'    => _n( '%s Policy not updated, somebody is editing it.', '%s Policies not updated, somebody is editing them.', $bulk_counts['locked'], 'foopeople' ),
					'deleted'   => _n( '%s Policy permanently deleted.', '%s Policies permanently deleted.', $bulk_counts['deleted'], 'foopeople' ),
					'trashed'   => _n( '%s Policy moved to the Trash.', '%s Policies moved to the Trash.', $bulk_counts['trashed'], 'foopeople' ),
					'untrashed' => _n( '%s Policy restored from the Trash.', '%s Policies restored from the Trash.', $bulk_counts['untrashed'], 'foopeople' ),
				)
			);

			return $bulk_messages;
		}
	}
}
