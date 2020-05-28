<?php 
if ( ! class_exists( 'PacePeople_Protoyping' ) ) {

	/**
	 * Class PacePeople_Protoyping
	 */
	class PacePeople_Protoyping {

		function __construct() {
			// add_filter( 'single_template', array( $this, 'load_person_single_template' ) );
		}

		function load_person_single_template($template) {
			global $wp_query, $post;			
			if ($post->post_type == PACEPEOPLE_CPT_PERSON && $template !== locate_template(array("person-single.php"))){
				return plugin_dir_path( __FILE__ ) .  "../templates/person-single.php";
			}
			return $template;
		}

		
	}
}