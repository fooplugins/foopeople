<?php
namespace FooPlugins\FooPeople\Taxonomies;

if( ! class_exists( 'FooPlugins\FooPeople\Taxonomies\Skill' ) ) {
 	class Skill {

		function __construct() {
			add_action( 'init', array( $this, 'register' ) );
		}

		function register() {
			$args = apply_filters( 'FooPlugins\FooPeople\Taxonomies\Skill\RegisterArgs',
				array(
					'labels' => array(
						'name'              => __( 'Skills', 'taxonomy general name', 'foopeople' ),
						'singular_name'     => __( 'Skill', 'taxonomy singular name', 'foopeople' ),
						'search_items'      => __( 'Search Skills', 'foopeople' ),
						'all_items'         => __( 'All Skills', 'foopeople' ),
						'parent_item'       => __( 'Parent Skill', 'foopeople' ),
						'parent_item_colon' => __( 'Parent Skill:', 'foopeople' ),
						'edit_item'         => __( 'Edit Skill', 'foopeople' ),
						'update_item'       => __( 'Update Skill', 'foopeople' ),
						'add_new_item'      => __( 'Add New Skill', 'foopeople' ),
						'new_item_name'     => __( 'New Skill Name', 'foopeople' ),
						'menu_name'         => __( 'Skills', 'foopeople' ),
					),
					'hierarchical' 		=> false,
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => FOOPEOPLE_CT_SKILL ),
				)
			);

			register_taxonomy( FOOPEOPLE_CT_SKILL, array( FOOPEOPLE_CPT_PERSON ), $args );
		}
	}
}
