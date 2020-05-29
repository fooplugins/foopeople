<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * Created by Andyf
 * Date: 26/12/2017
 *
 */
if( ! class_exists( 'Person_Metabox_Preview' ) ) {
 	class Person_Metabox_Preview {

		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			// add_action( 'admin_enqueue_scripts', array( $this, 'init_stylesheets_and_scripts' ) );
		}

		function is_foopeople_admin_screen() {
			$screen = get_current_screen();
			if($screen) {
				if($screen->post_type == FOOPEOPLE_CPT_PERSON && $screen->id == FOOPEOPLE_CPT_PERSON) {
					return true;
				}
			}
			return false;
		}

		// function init_stylesheets_and_scripts() {
		// 	if( $this->is_foopeople_admin_screen() ) {
		// 		wp_register_style( 'foopeople_preview_styles', plugin_dir_url(dirname( __FILE__ )) . '../assets/css/foopeople.admin.min.css', array(), '' );
		// 		wp_enqueue_style( 'foopeople_preview_styles' );

		// 		wp_register_script( 'foopeople_preview_scripts', plugin_dir_url(dirname( __FILE__ )) . '../assets/js/admin.min.js', array( 'jquery' ), '', true );
		// 		wp_enqueue_script( 'foopeople_preview_scripts' );
		// 	}
		// }

		function render_preview_template($filename) {
			ob_start();
			load_template(dirname( __FILE__ ) . '/../templates/admin/'.$filename.'.php');
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		function pacepeople_admin_person_preview() {
			if( $this->is_foopeople_admin_screen() ) {
				echo $this->render_preview_template('person-single-item');
			}
		}

		function init() {
			add_action( 'edit_form_after_title', array( $this, 'pacepeople_admin_person_preview') );

		}
	}
}