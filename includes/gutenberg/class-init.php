<?php
namespace FooPlugins\FooPeople\Gutenberg;

/**
 * FooPeople Gutenberg Functionality
 */


if ( ! class_exists( 'FooPlugins\FooPeople\Gutenberg\Init' ) ) {

	class Init {

		function __construct() {
			new namespace\Blocks();

			add_action( 'attach_people_to_post', array( $this, 'attach_people_to_post' ), 10, 2 );
		}

		/**
		 * Use the built-in Block Parser to "attach" people to a post
		 *
		 * @param $post_id
		 * @param $post
		 */
		function attach_people_to_post( $post_id, $post ) {
			if ( !class_exists( 'WP_Block_Parser' ) ) {
				return;
			}

			if ( !is_object( $post ) ) {
				return;
			}

			$parser = new WP_Block_Parser();
			$blocks = $parser->parse( $post->post_content );

			var_dump($blocks);

			// foreach ( $blocks as $block ) {
			// 	if ( array_key_exists( 'id', $block['attrs'] ) ) {
			// 		$gallery_id = $block['attrs']['id'];

			// 		add_post_meta( $post_id, FOOGALLERY_META_POST_USAGE, $gallery_id, false );

			// 		do_action( 'attach_people_to_post', $post_id, $gallery_id );
			// 	}
			// }
		}
	}
}