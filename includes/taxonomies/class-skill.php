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
						'name'              => __( 'Skills', 'taxonomy general name', FOOPEOPLE_SLUG ),
						'singular_name'     => __( 'Skill', 'taxonomy singular name', FOOPEOPLE_SLUG ),
						'search_items'      => __( 'Search Skills', FOOPEOPLE_SLUG ),
						'all_items'         => __( 'All Skills', FOOPEOPLE_SLUG ),
						'parent_item'       => __( 'Parent Skill', FOOPEOPLE_SLUG ),
						'parent_item_colon' => __( 'Parent Skill:', FOOPEOPLE_SLUG ),
						'edit_item'         => __( 'Edit Skill', FOOPEOPLE_SLUG ),
						'update_item'       => __( 'Update Skill', FOOPEOPLE_SLUG ),
						'add_new_item'      => __( 'Add New Skill', FOOPEOPLE_SLUG ),
						'new_item_name'     => __( 'New Skill Name', FOOPEOPLE_SLUG ),
						'menu_name'         => __( 'Skills', FOOPEOPLE_SLUG ),
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
