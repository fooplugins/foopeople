<?php
/**
 * Created by Andyf
 * Date: 26/12/2017
 *
 */
if( ! class_exists( 'PacePeople_Admin_People_MetaBox_Preview' ) ) {
 	class PacePeople_Admin_People_MetaBox_Preview {

		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'init_stylesheets_and_scripts' ) );
		}

		function is_pacepeople_admin_screen() {
			$screen = get_current_screen();
			if($screen) {
				if($screen->post_type == PACEPEOPLE_CPT_PERSON && $screen->id == PACEPEOPLE_CPT_PERSON) {
					return true;
				}
			}
			return false;
		}

		function init_stylesheets_and_scripts() {
			if( $this->is_pacepeople_admin_screen() ) {
				wp_register_style( 'pacepeople_preview_styles', plugin_dir_url(dirname( __FILE__ )) . '../css/pacepeople.admin.min.css', array(), '' );
				wp_enqueue_style( 'pacepeople_preview_styles' );

				wp_register_script( 'pacepeople_preview_scripts', plugin_dir_url(dirname( __FILE__ )) . '../js/pacepeople.admin.min.js', array( 'jquery' ), '', true );
				wp_enqueue_script( 'pacepeople_preview_scripts' );			
			}
		}

		function render_preview_template($filename) {
			ob_start();
			load_template(dirname( __FILE__ ) . '/../templates/admin/'.$filename.'.php');
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}		

		function pacepeople_admin_person_preview() {
			if( $this->is_pacepeople_admin_screen() ) {
				echo $this->render_preview_template('person-single-item');
			}
		}

		function init() {
			add_action( 'edit_form_after_title', array( $this, 'pacepeople_admin_person_preview') );
			
		}
	}
}