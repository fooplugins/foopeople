<?php
namespace FooPlugins\FooPeople\PostTypes;

/*
 * FooPeople Role Custom Post Type
 */

if ( ! class_exists( 'FooPlugins\FooPeople\PostTypes\Role' ) ) {

	class Role {


		function __construct() {
			//register the post types
			add_action( 'init', array( $this, 'register_post_type' ) );
			add_action( 'admin_menu', array( $this, 'register_menu_items' ) );
		}

		function register_post_type() {
			//allow extensions to override the role post type
			$args = apply_filters( 'FooPlugins\FooPeople\PostTypes\Role\RegisterArgs',
				array(
					'labels'        => array(
						'name'               => __( 'Job Roles', 'foopeople' ),
						'singular_name'      => __( 'Job Role', 'foopeople' ),
						'add_new'            => __( 'Add Job Role', 'foopeople' ),
						'add_new_item'       => __( 'Add New Job Role', 'foopeople' ),
						'edit_item'          => __( 'Edit Job Role', 'foopeople' ),
						'new_item'           => __( 'New Job Role', 'foopeople' ),
						'view_item'          => __( 'View Job Role', 'foopeople' ),
						'search_items'       => __( 'Search Job Roles', 'foopeople' ),
						'not_found'          => __( 'No job roles found', 'foopeople' ),
						'not_found_in_trash' => __( 'No job roles found in Trash', 'foopeople' ),
						'menu_name'          => __( 'Job Roles', 'foopeople' ),
						'all_items'          => __( 'Job Roles', 'foopeople' )
					),
					'hierarchical'  => true,
					'public'        => false,
					'show_ui'       => true,
					'show_in_menu'  => false,
					'menu_icon'     => 'dashicons-groups',
					'supports'      => array( 'thumbnail', 'title' ),
				)
			);

			register_post_type( FOOPEOPLE_CPT_ROLE, $args );
		}

		function register_menu_items() {
			add_submenu_page(
				foopeople_admin_menu_parent_slug(),
				__( 'Job Roles', 'foopeople' ),
				__( 'Job Roles', 'foopeople' ),
				'manage_options',
				'edit.php?post_type=' . FOOPEOPLE_CPT_ROLE
			);
		}
	}
}
