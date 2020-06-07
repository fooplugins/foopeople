<?php
namespace FooPlugins\FooPeople\Admin\Person;

/**
 * Class for showing the person preview metabox
 */
if( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxPersonPreview' ) ) {
 	class MetaboxPersonPreview {

		function __construct() {
			add_action( 'edit_form_after_title', array( $this, 'person_preview') );
		}

		function person_preview() {
			if ( foopeople_is_admin_screen( FOOPEOPLE_CPT_PERSON ) ) {
				foopeople_render_template( 'admin', 'person-preview' );
			}
		}
	}
}
