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
						'name'              => __( 'Skills', 'taxonomy general name', 'pacepeople' ),
						'singular_name'     => __( 'Skill', 'taxonomy singular name', 'pacepeople' ),
						'search_items'      => __( 'Search Skills', 'pacepeople' ),
						'all_items'         => __( 'All Skills', 'pacepeople' ),
						'parent_item'       => __( 'Parent Skill', 'pacepeople' ),
						'parent_item_colon' => __( 'Parent Skill:', 'pacepeople' ),
						'edit_item'         => __( 'Edit Skill', 'pacepeople' ),
						'update_item'       => __( 'Update Skill', 'pacepeople' ),
						'add_new_item'      => __( 'Add New Skill', 'pacepeople' ),
						'new_item_name'     => __( 'New Skill Name', 'pacepeople' ),
						'menu_name'         => __( 'Skills', 'pacepeople' ),
					),
					'hierarchical' 		=> true,
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
