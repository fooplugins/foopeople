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
		}

		function person_preview() {
			if( is_foopeople_admin_screen() ) {
				echo render_template('admin', 'person-preview');
			}
		}

		function init() {
			add_action( 'edit_form_after_title', array( $this, 'person_preview') );
		}
	}
}
