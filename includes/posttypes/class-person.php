<?php
namespace FooPlugins\FooPeople\PostTypes;

/*
 * FooPeople Person Custom Post Type
 */

if ( ! class_exists( 'FooPlugins\FooPeople\PostTypes\Person' ) ) {

	class Person {


		function __construct() {
			//register the post types
			add_action( 'init', array( $this, 'register_post_type' ) );

			add_action( 'init', array( $this, 'register_post_statuses' ), 9 );

			//update post type messages
			add_filter( 'post_updated_messages', array( $this, 'update_messages' ) );

			//update post bulk messages
			add_filter( 'bulk_post_updated_messages', array( $this, 'update_bulk_messages' ), 10, 2 );

			// Add single person page template
			add_filter('single_template', array( $this,'load_single_person_template' ) );

			// Add a column for the person portrait
			add_filter('manage_foopeople-person_posts_columns', array( $this, 'add_portrait_column') );

			// Add the portrait to the column we created
			add_action('manage_foopeople-person_posts_custom_column', array( $this, 'show_portrait_column' ), 10, 2);

			add_action('admin_head', array( $this, 'people_dashboard_styles' ) );
		}


		function register_post_type() {
			$people_issues = 0;
			$person_menu_name = __( 'Foo People', 'foopeople' );
			if ( $people_issues > 0 ) {
				//TODO : change the menu. 2 options:
				//  - https://wordpress.stackexchange.com/questions/89028/put-update-like-notification-bubble-on-multiple-cpts-menus-for-pending-items
				//  - https://wordpress.stackexchange.com/questions/113235/add-number-new-posts-post-status-pending-to-administration-menu
				//  - https://wisdmlabs.com/blog/display-dashboard-notifications-custom-post-types-menus/
				$person_menu_name .= sprintf( ' <span class="awaiting-mod">%d</span>', $people_issues );
			}

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
						'menu_name'          => $person_menu_name,
						'all_items'          => __( 'People', 'foopeople' ),
						'featured_image'        => __( 'Portrait', 'foopeople' ),
						'set_featured_image'    => __( 'Set Portrait', 'foopeople' ),
						'remove_featured_image' => _x( 'Remove Portrait', 'foopeople' ),
						'use_featured_image'    => _x( 'Use as Portrait', 'foopeople' ),
					),
					'hierarchical'  => true,
					'show_in_rest'  => true,
					'public'        => true,
					'show_ui'       => true,
					'show_in_menu'  => true,
					'rewrite'       => array( 'slug' => 'person' ),
					'menu_icon'     => 'dashicons-universal-access-alt',
					'supports'      => array( 'thumbnail', 'title' ),
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




		function register_post_statuses() {
			//TODO : https://wordpress.stackexchange.com/a/314236/17769

			$statuses = apply_filters(
				'FooPlugins\FooPeople\PostTypes\Person\Statuses',
				array(
					'onbaording'    => array(
						'label'                     => _x( 'Onboarding', 'Person status', 'fooepeople' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => _n_noop( 'Onboarding <span class="count">(%s)</span>', 'Onboarding <span class="count">(%s)</span>', 'fooepeople' ),
					),
					'active'    => array(
						'label'                     => _x( 'Active', 'Person status', 'fooepeople' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => _n_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', 'fooepeople' ),
					),
					'terminated' => array(
						'label'                     => _x( 'Terminated', 'Person status', 'fooepeople' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => _n_noop( 'Terminated <span class="count">(%s)</span>', 'Terminated <span class="count">(%s)</span>', 'fooepeople' ),
					),
					'deceased'    => array(
						'label'                     => _x( 'Deceased', 'Person status', 'fooepeople' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => _n_noop( 'Deceased <span class="count">(%s)</span>', 'Deceased <span class="count">(%s)</span>', 'fooepeople' ),
					),
					'resigned'  => array(
						'label'                     => _x( 'Resigned', 'Person status', 'fooepeople' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => _n_noop( 'Resigned <span class="count">(%s)</span>', 'Resigned <span class="count">(%s)</span>', 'fooepeople' ),
					),
				)
			);

			foreach ( $statuses as $status => $args ) {
				register_post_status( $status, $args );
			}
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


		/**
		 * Add a column to the people dashboard
		 *
		 * @global object $post     The current post object.
		 *
		 * @param array   $defaults The current columns in place
		 *
		 * @return array $column Adjusted columns to use
		 */
		function add_portrait_column($defaults) {

			$i = 1;
			$columns = array();
			foreach( $defaults as $key => $value ) {
				$columns[$key] = $value;
				if ( 1 == $i++ ) {
					$columns['portrait'] = 'Portrait';
				}
				if ( 4 == $i++ ) {
					$columns['jobtitle'] = 'Job Title';
					$columns['workemail'] = 'Email';
					$columns['employeenumber'] = 'Employee Number';
				}
			}
			return $columns;
		}

		/**
		 * Add a portrait to our new column in the people dashboard
		 *
		 * @global object $post   The current post object.
		 *
		 * @param array   $column_name The column name
		 * @param array   $post_id The current post
		 *
		 */
		function show_portrait_column($column_name, $post_id) {
			$postmeta = get_post_meta( $post_id, FOOPEOPLE_META_PERSON_MAIN, true );

			switch ($column_name) {
				case 'portrait':
					echo get_the_post_thumbnail($post_id, 'thumbnail');
				break;
				case 'jobtitle':
					if ( isset( $postmeta['jobtitle'] ) ) :
						echo $postmeta['jobtitle'];
					endif;
				break;
				case 'workemail':
					if ( isset( $postmeta['workemail'] ) ) :
						echo '<a target="_blank" href="mailto:';
						echo $postmeta['workemail'];
						echo '">';
						echo $postmeta['workemail'];
						echo '</a>';
					endif;
				break;
				case 'employeenumber':
					if ( isset( $postmeta['employeenumber'] ) ) :
						echo $postmeta['employeenumber'];
					endif;
				break;
			}
		}


		/**
		 * Add custom styling to the people dashboard
		 *
		 * @return string
		 *
		 */
		function people_dashboard_styles() {
			echo '<style>
				body.post-type-foopeople-person table.fixed {
					table-layout: auto;
				}
				body.post-type-foopeople-person td.portrait.column-portrait,
				body.post-type-foopeople-person td.portrait.column-portrait img  {
					width: 50px;
					height: auto;
				}

				body.post-type-foopeople-person .subsubsub li.publish {
					display: none;
				}
			</style>';
		}



	}
}
