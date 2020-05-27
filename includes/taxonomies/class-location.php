<?php
namespace FooPlugins\FooPeople\Taxonomies;

if( ! class_exists( 'FooPlugins\FooPeople\Taxonomies\Location' ) ) {
 	class Location {

		function __construct() {
			add_action( 'init', array( $this, 'register' ) );
		}

		function register() {
			$args = apply_filters( 'FooPlugins\FooPeople\Taxonomies\Location\RegisterArgs',
				array(
					'labels' => array(
						'name'              => __( 'Locations', 'taxonomy general name', 'foopeople' ),
						'singular_name'     => __( 'Location', 'taxonomy singular name', 'foopeople' ),
						'search_items'      => __( 'Search Locations', 'foopeople' ),
						'all_items'         => __( 'All Locations', 'foopeople' ),
						'parent_item'       => __( 'Parent Location', 'foopeople' ),
						'parent_item_colon' => __( 'Parent Location:', 'foopeople' ),
						'edit_item'         => __( 'Edit Location', 'foopeople' ),
						'update_item'       => __( 'Update Location', 'foopeople' ),
						'add_new_item'      => __( 'Add New Location', 'foopeople' ),
						'new_item_name'     => __( 'New Location'.' Name', 'foopeople' ),
						'menu_name'         => __( 'Locations', 'foopeople' ),
					),
					'hierarchical' 		=> true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => FOOPEOPLE_CT_LOCATION ),
				)
			);

			register_taxonomy( FOOPEOPLE_CT_LOCATION, array( FOOPEOPLE_CPT_PERSON ), $args );

		}
	}
}
