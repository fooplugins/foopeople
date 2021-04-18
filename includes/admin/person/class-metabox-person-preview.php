<?php
namespace FooPlugins\FooPeople\Admin\Person;

/**
 * Class for showing the person preview metabox
 */
if( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxPersonPreview' ) ) {
 	class MetaboxPersonPreview {

		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'create_preview_metabox') );
		}

		function create_preview_metabox() {
			add_meta_box( 'post-ppl-preview-metabox', __( 'Person Preview', 'textdomain' ), array( $this, 'person_preview'), FOOPEOPLE_CPT_PERSON,  'side');
		}

		function person_preview() {
			foopeople_render_template( 'admin', 'person-preview' );
		}

	}
}
