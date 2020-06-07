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
			add_action( 'edit_form_after_title', array( $this, 'person_preview') );
		}

		function person_preview() {
			if ( foopeople_is_admin_screen( FOOPEOPLE_CPT_PERSON ) ) {
				echo foopeople_render_template( 'admin', 'person-preview' );
			}
		}
	}
}
