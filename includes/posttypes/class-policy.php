<?php
namespace FooPlugins\FooPeople;

/*
 * Policy Custom Post Type
 */

if ( ! class_exists( 'PacePeople_Policy_PostType' ) ) {

	class PacePeople_Policy_PostType {

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
			$args = apply_filters( 'pacepeople_policy_posttype_register_args',
				array(
					'labels'        => array(
						'name'               => __( PACEPEOPLE_MULTIPLE_POLICY, 'pacepeople' ),
						'singular_name'      => __( PACEPEOPLE_SINGULAR_POLICY, 'pacepeople' ),
						'add_new'            => __( 'Add '.PACEPEOPLE_SINGULAR_POLICY, 'pacepeople' ),
						'add_new_item'       => __( 'Add New '.PACEPEOPLE_SINGULAR_POLICY, 'pacepeople' ),
						'edit_item'          => __( 'Edit '.PACEPEOPLE_SINGULAR_POLICY, 'pacepeople' ),
						'new_item'           => __( 'New '.PACEPEOPLE_MULTIPLE_POLICY, 'pacepeople' ),
						'view_item'          => __( 'View '.PACEPEOPLE_MULTIPLE_POLICY, 'pacepeople' ),
						'search_items'       => __( 'Search '.PACEPEOPLE_MULTIPLE_POLICY, 'pacepeople' ),
						'not_found'          => __( 'No '.PACEPEOPLE_MULTIPLE_POLICY.' found', 'pacepeople' ),
						'not_found_in_trash' => __( 'No '.PACEPEOPLE_MULTIPLE_POLICY.' found in Trash', 'pacepeople' ),
						'menu_name'          => __( PACEPEOPLE_MULTIPLE_POLICY, 'pacepeople' ),
						'all_items'          => __( PACEPEOPLE_MULTIPLE_POLICY, 'pacepeople' )
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

			register_post_type( PACEPEOPLE_CPT_POLICY, $args );
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
			$messages[PACEPEOPLE_CPT_POLICY] = apply_filters( 'pacepeople_policy_posttype_update_messages',
				array(
					0  => '',
					1  => __( PACEPEOPLE_SINGULAR_POLICY.' updated.', 'pacepeople' ),
					2  => __( PACEPEOPLE_SINGULAR_POLICY.' custom field updated.', 'pacepeople' ),
					3  => __( PACEPEOPLE_SINGULAR_POLICY.' custom field deleted.', 'pacepeople' ),
					4  => __( PACEPEOPLE_SINGULAR_POLICY.' updated.', 'pacepeople' ),
					5  => isset($_GET['revision']) ? sprintf( __( PACEPEOPLE_SINGULAR_POLICY.' restored to revision from %s.', 'pacepeople' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6  => __( PACEPEOPLE_SINGULAR_POLICY.' published.', 'pacepeople' ),
					7  => __( PACEPEOPLE_SINGULAR_POLICY.' saved.', 'pacepeople' ),
					8  => __( PACEPEOPLE_SINGULAR_POLICY.' submitted.', 'pacepeople' ),
					9  => sprintf( __( 'People scheduled for: <strong>%1$s</strong>.', 'pacepeople' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
					10 => __( PACEPEOPLE_SINGULAR_POLICY.' draft updated.', 'pacepeople' )
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

			$bulk_messages[PACEPEOPLE_CPT_POLICY] = apply_filters( 'pacepeople_policy_posttype_bulk_update_messages',
				array(
					'updated'   => _n( '%s '.PACEPEOPLE_SINGULAR_POLICY.' updated.', '%s People updated.', $bulk_counts['updated'], 'pacepeople' ),
					'locked'    => _n( '%s '.PACEPEOPLE_SINGULAR_POLICY.' not updated, somebody is editing it.', '%s '.PACEPEOPLE_MULTIPLE_POLICY.' not updated, somebody is editing them.', $bulk_counts['locked'], 'pacepeople' ),
					'deleted'   => _n( '%s '.PACEPEOPLE_SINGULAR_POLICY.' permanently deleted.', '%s '.PACEPEOPLE_MULTIPLE_POLICY.' permanently deleted.', $bulk_counts['deleted'], 'pacepeople' ),
					'trashed'   => _n( '%s '.PACEPEOPLE_SINGULAR_POLICY.' moved to the Trash.', '%s '.PACEPEOPLE_MULTIPLE_POLICY.' moved to the Trash.', $bulk_counts['trashed'], 'pacepeople' ),
					'untrashed' => _n( '%s '.PACEPEOPLE_SINGULAR_POLICY.' restored from the Trash.', '%s '.PACEPEOPLE_MULTIPLE_POLICY.' restored from the Trash.', $bulk_counts['untrashed'], 'pacepeople' ),
				)
			);

			return $bulk_messages;
		}
	}
}
