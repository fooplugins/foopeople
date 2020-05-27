<?php
namespace FooPlugins\FooPeople\Taxonomies;


if( ! class_exists( 'FooPlugins\FooPeople\Taxonomies\Team' ) ) {

 	class Team {

		function __construct() {
			add_action( 'init', array( $this, 'register' ) );
		}

		function register() {
			$args = apply_filters( 'FooPlugins\FooPeople\Taxonomies\Team\RegisterArgs',
				array(
					'labels' => array(
						'name'              => __( 'Teams', 'taxonomy general name', 'foopeople' ),
						'singular_name'     => __( 'Teams', 'taxonomy singular name', 'foopeople' ),
						'search_items'      => __( 'Search '.'Teams', 'foopeople' ),
						'all_items'         => __( 'All Teams', 'foopeople' ),
						'parent_item'       => __( 'Parent Team', 'foopeople' ),
						'parent_item_colon' => __( 'Parent Team:', 'foopeople' ),
						'edit_item'         => __( 'Edit Team', 'foopeople' ),
						'update_item'       => __( 'Update Team', 'foopeople' ),
						'add_new_item'      => __( 'Add New Team', 'foopeople' ),
						'new_item_name'     => __( 'New Team Name', 'foopeople' ),
						'menu_name'         => __( 'Teams', 'foopeople' ),
					),
					'hierarchical' 		=> true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => FOOPEOPLE_CT_TEAM ),
				)
			);

			register_taxonomy( FOOPEOPLE_CT_TEAM, array( FOOPEOPLE_CPT_PERSON ), $args );
		}
	}
}
