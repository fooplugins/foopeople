<?php
namespace FooPlugins\FooPeople\Admin;

/*
 * foopeople Admin People MetaBoxes class
 */

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person_Metaboxes' ) ) {

	class Person_Metaboxes {

		public function __construct() {
			//add the metaboxes for a person
			add_action( 'add_meta_boxes_' . FOOPEOPLE_CPT_PERSON, array( $this, 'add_meta_boxes' ) );

			//enqueue assets needed for field groups
			// add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			//save extra post data for a person
			add_action( 'save_post', array( $this, 'save_person' ) );
//
//			//save custom field on a page or post
//			add_Action( 'save_post', array( $this, 'attach_people_to_post' ), 10, 2 );
//
//			//whitelist metaboxes for our people postype
//			add_filter( 'foopeople_metabox_sanity', array( $this, 'whitelist_metaboxes' ) );
//
//			//add scripts used by metaboxes
//			add_action( 'admin_enqueue_scripts', array( $this, 'include_required_scripts' ) );
//
//			// Ajax calls for creating a page for the people
//			add_action( 'wp_ajax_foopeople_create_people_page', array( $this, 'ajax_create_people_page' ) );
//
//			// Ajax call for clearing thumb cache for the people
//			add_action( 'wp_ajax_foopeople_clear_people_thumb_cache', array( $this, 'ajax_clear_people_thumb_cache' ) );
//
//			// Ajax call for generating a people preview
//			add_action( 'wp_ajax_foopeople_preview', array( $this, 'ajax_people_preview' ) );
//
//			//handle previews that have no attachments
//			add_action( 'foopeople_template_no_attachments', array( $this, 'preview_no_attachments' ) );
		}

		/**
		 * Add metaboxes to the person CPT
		 * @param $post
		 */
		public function add_meta_boxes( $post ) {
			add_meta_box(
				FOOPEOPLE_CPT_PERSON . '_details',
				__( 'Person Details', FOOPEOPLE_SLUG ),
				array( $this, 'render_person_details_metabox' ),
				FOOPEOPLE_CPT_PERSON,
				'normal',
				'high'
			);
		}

		/**
		 * Render the details metabox contents
		 * @param $post
		 */
		public function render_person_details_metabox( $post ) {
			//get the person object
			$person = new Person( $post );

			//build up the fields using the person instance
			$field_group = $person->get_details_field_group();

			Metabox_Field_Group::render_field_group( $field_group );

			?>
            <input type="hidden" name="<?php echo FOOPEOPLE_CPT_PERSON; ?>_nonce"
                   id="<?php echo FOOPEOPLE_CPT_PERSON; ?>_nonce"
                   value="<?php echo wp_create_nonce( plugin_basename( FOOPEOPLE_FILE ) ); ?>"/>
            <?php
		}

		/***
		 * Enqueue the assets needed by the settings
		 * @param $hook_suffix
		 */
		// function enqueue_assets( $hook_suffix ){
		// 	if( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
		// 		$screen = get_current_screen();

		// 		if ( is_object( $screen ) && FOOPEOPLE_CPT_PERSON == $screen->post_type ){
		// 			Metabox_Field_Group::enqueue_assets();
		// 		}
		// 	}
		// }

		/**
		 * Hook into the save post and save the fields
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function save_person( $post_id ) {
			// check autosave first
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// verify nonce
			if ( array_key_exists( FOOPEOPLE_CPT_PERSON . '_nonce', $_POST ) &&
				wp_verify_nonce( $_POST[FOOPEOPLE_CPT_PERSON . '_nonce'], plugin_basename( FOOPEOPLE_FILE ) ) ) {
				//if we get here, we are dealing with the person custom post type

				do_action( FOOPEOPLE_ACTION_ADMIN_PERSON_BEFORE_SAVE, $post_id, $_POST );

				//save the person details
				$details = isset( $_POST[FOOPEOPLE_PERSON_META_DETAILS] ) ? $_POST[FOOPEOPLE_PERSON_META_DETAILS] : array();
                update_post_meta( $post_id, FOOPEOPLE_PERSON_META_DETAILS, $details );

                //update the search index after a person is saved
                $person = Person::get_by_id( $post_id );
                $search_index = $person->build_search_index();
                update_post_meta( $post_id, FOOPEOPLE_PERSON_META_SEARCH, $search_index );

				do_action( FOOPEOPLE_ACTION_ADMIN_PERSON_AFTER_SAVE, $post_id, $_POST );
			}
		}

		public function attach_people_to_post( $post_id, $post ) {

			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			//only do this check for a page or post
			if ( 'post' == $post->post_type ||
				'page' == $post->post_type ) {

                do_action( 'foopeople_start_attach_people_to_post', $post_id );

				//Clear any foopeople usages that the post might have
				delete_post_meta( $post_id, FOOPEOPLE_META_POST_USAGE );

				//get all foopeople shortcodes that are on the page/post
				$people_shortcodes = foopeople_extract_people_shortcodes( $post->post_content );

                if ( is_array( $people_shortcodes ) && count( $people_shortcodes ) > 0 ) {

                    foreach ( $people_shortcodes as $id => $shortcode ) {
                        //if the content contains the foopeople shortcode then add a custom field
                        add_post_meta( $post_id, FOOPEOPLE_META_POST_USAGE, $id, false );

                        do_action( 'foopeople_attach_people_to_post', $post_id, $id );
                    }
                }
			}
		}



		public function render_people_shortcode_metabox( $post ) {
			$people = $this->get_people( $post );
			$shortcode = $people->shortcode();
			?>
			<p class="foopeople-shortcode">
				<input type="text" id="foopeople-copy-shortcode" size="<?php echo strlen( $shortcode ) + 2; ?>" value="<?php echo htmlspecialchars( $shortcode ); ?>" readonly="readonly" />
			</p>
			<p>
				<?php _e( 'Paste the above shortcode into a post or page to show the people.', FOOPEOPLE_SLUG ); ?>
			</p>
			<script>
				jQuery(function($) {
					var shortcodeInput = document.querySelector('#foopeople-copy-shortcode');
					shortcodeInput.addEventListener('click', function () {
						try {
							// select the contents
							shortcodeInput.select();
							//copy the selection
							document.execCommand('copy');
							//show the copied message
							$('.foopeople-shortcode-message').remove();
							$(shortcodeInput).after('<p class="foopeople-shortcode-message"><?php _e( 'Shortcode copied to clipboard :)',FOOPEOPLE_SLUG ); ?></p>');
						} catch(err) {
							console.log('Oops, unable to copy!');
						}
					}, false);
				});
			</script>
			<?php
		}

		public function render_people_usage_metabox( $post ) {
			$people = $this->get_people( $post );
			$posts = $people->find_usages();
			if ( $posts && count( $posts ) > 0 ) { ?>
				<p>
					<?php _e( 'This people is used on the following posts or pages:', FOOPEOPLE_SLUG ); ?>
				</p>
				<ul class="ul-disc">
				<?php foreach ( $posts as $post ) {
					$url = get_permalink( $post->ID );
					echo '<li>' . $post->post_title . '&nbsp;';
					edit_post_link( __( 'Edit', FOOPEOPLE_SLUG ), '<span class="edit">', ' | </span>', $post->ID );
					echo '<span class="view"><a href="' . esc_url( $url ) . '" target="_blank">' . __( 'View', FOOPEOPLE_SLUG ) . '</a></li>';
				} ?>
				</ul>
			<?php } else { ?>
				<p>
					<?php _e( 'This people is not used on any pages or pages yet. Quickly create a page:', FOOPEOPLE_SLUG ); ?>
				</p>
				<div class="foopeople_metabox_actions">
					<button class="button button-primary button-large" id="foopeople_create_page"><?php _e( 'Create People Page', FOOPEOPLE_SLUG ); ?></button>
					<span id="foopeople_create_page_spinner" class="spinner"></span>
					<?php wp_nonce_field( 'foopeople_create_people_page', 'foopeople_create_people_page_nonce', false ); ?>
				</div>
				<p>
					<?php _e( 'A draft page will be created which includes the people shortcode in the content. The title of the page will be the same title as the people.', FOOPEOPLE_SLUG ); ?>
				</p>
			<?php }
		}

		public function render_sorting_metabox( $post ) {
			$people = $this->get_people( $post );
			$sorting_options = foopeople_sorting_options();
			if ( empty( $people->sorting ) ) {
				$people->sorting = '';
			}
			?>
			<p>
				<?php _e('Change the way images are sorted within your people. By default, they are sorted in the order you see them.', FOOPEOPLE_SLUG); ?>
			</p>
			<?php
			foreach ( $sorting_options as $sorting_key => $sorting_label ) { ?>
				<p>
				<input type="radio" value="<?php echo $sorting_key; ?>" <?php checked( $sorting_key === $people->sorting ); ?> id="foopeopleSettings_PeopleSort_<?php echo $sorting_key; ?>" name="<?php echo foopeople_META_SORT; ?>" />
				<label for="foopeopleSettings_PeopleSort_<?php echo $sorting_key; ?>"><?php echo $sorting_label; ?></label>
				</p><?php
			} ?>
			<p class="foopeople-help">
				<?php _e('PLEASE NOTE : sorting randomly will force HTML Caching for the people to be disabled.', FOOPEOPLE_SLUG); ?>
			</p>
			<?php
		}

		public function render_retina_metabox( $post ) {
			$people = $this->get_people( $post );
			$retina_options = foopeople_retina_options();
			if ( empty( $people->retina ) ) {
				$people->retina = foopeople_get_setting( 'default_retina_support', array() );
			}
			?>
			<p>
				<?php _e('Add retina support to this people by choosing the different pixel densities you want to enable.', FOOPEOPLE_SLUG); ?>
			</p>
			<?php
			foreach ( $retina_options as $retina_key => $retina_label ) {
				$checked = array_key_exists( $retina_key, $people->retina ) ? ('true' === $people->retina[$retina_key]) : false;
				?>
				<p>
				<input type="checkbox" value="true" <?php checked( $checked ); ?> id="foopeopleSettings_Retina_<?php echo $retina_key; ?>" name="<?php echo foopeople_META_RETINA; ?>[<?php echo $retina_key; ?>]" />
				<label for="foopeopleSettings_Retina_<?php echo $retina_key; ?>"><?php echo $retina_label; ?></label>
				</p><?php
			} ?>
			<p class="foopeople-help">
				<?php _e('PLEASE NOTE : thumbnails will be generated for each of the pixel densities chosen, which will increase your website\'s storage space!', FOOPEOPLE_SLUG); ?>
			</p>
			<?php
		}

		public function render_thumb_settings_metabox( $post ) {
			$people = $this->get_people( $post );
			$force_use_original_thumbs = get_post_meta( $post->ID, foopeople_META_FORCE_ORIGINAL_THUMBS, true );
			$checked = 'true' === $force_use_original_thumbs; ?>
			<p>
				<?php _e( 'Clear all the previously cached thumbnails that have been generated for this people.', FOOPEOPLE_SLUG ); ?>
			</p>
			<div class="foopeople_metabox_actions">
				<button class="button button-primary button-large" id="foopeople_clear_thumb_cache"><?php _e( 'Clear Thumbnail Cache', FOOPEOPLE_SLUG ); ?></button>
				<span id="foopeople_clear_thumb_cache_spinner" class="spinner"></span>
				<?php wp_nonce_field( 'foopeople_clear_people_thumb_cache', 'foopeople_clear_people_thumb_cache_nonce', false ); ?>
			</div>
			<p>
				<input type="checkbox" value="true" <?php checked( $checked ); ?> id="foopeopleSettings_ForceOriginalThumbs" name="<?php echo foopeople_META_FORCE_ORIGINAL_THUMBS; ?>" />
				<label for="foopeopleSettings_ForceOriginalThumbs"><?php _e('Force Original Thumbs', FOOPEOPLE_SLUG); ?></label>
			</p>
			<?php
		}

		public function include_required_scripts() {
			$screen_id = foo_current_screen_id();

			//only include scripts if we on the foopeople add/edit page
			if ( FOOPEOPLE_CPT_PERSON === $screen_id ||
			     'edit-' . FOOPEOPLE_CPT_PERSON === $screen_id ) {

				//enqueue any dependencies from extensions or people templates
				do_action( 'foopeople_enqueue_preview_dependencies' );
				//add core foopeople files for preview
				foopeople_enqueue_core_people_template_style();
				foopeople_enqueue_core_people_template_script();

				//spectrum needed for the colorpicker field
				$url = FOOPEOPLE_URL . 'lib/spectrum/spectrum.js';
				wp_enqueue_script( 'foopeople-spectrum', $url, array('jquery'), FOOPEOPLE_VERSION );
				$url = FOOPEOPLE_URL . 'lib/spectrum/spectrum.css';
				wp_enqueue_style( 'foopeople-spectrum', $url, array(), FOOPEOPLE_VERSION );

				//include any admin js required for the templates
				foreach ( foopeople_people_templates() as $template ) {
					$admin_js = foo_safe_get( $template, 'admin_js' );
					if ( is_array( $admin_js ) ) {
						//dealing with an array of js files to include
						foreach( $admin_js as $admin_js_key => $admin_js_src ) {
							wp_enqueue_script( 'foopeople-people-admin-' . $template['slug'] . '-' . $admin_js_key, $admin_js_src, array('jquery', 'media-upload', 'jquery-ui-sortable'), FOOPEOPLE_VERSION );
						}
					} else {
						//dealing with a single js file to include
						wp_enqueue_script( 'foopeople-people-admin-' . $template['slug'], $admin_js, array('jquery', 'media-upload', 'jquery-ui-sortable'), FOOPEOPLE_VERSION );
					}
				}
			}
		}

		public function render_customcss_metabox( $post ) {
			$people = $this->get_people( $post );
			$custom_css = $people->custom_css;
			$example = '<code>#foopeople-people-' . $post->ID . ' { }</code>';
			?>
			<p>
				<?php printf( __( 'Add any custom CSS to target this specific people. For example %s', FOOPEOPLE_SLUG ), $example ); ?>
			</p>
			<table id="table_styling" class="form-table">
				<tbody>
				<tr>
					<td>
						<textarea class="foopeople_metabox_custom_css" name="<?php echo foopeople_META_CUSTOM_CSS; ?>" type="text"><?php echo $custom_css; ?></textarea>
					</td>
				</tr>
				</tbody>
			</table>
			<?php
		}

		public function ajax_create_people_page() {
			if ( check_admin_referer( 'foopeople_create_people_page', 'foopeople_create_people_page_nonce' ) ) {

				$foopeople_id = $_POST['foopeople_id'];

				$foopeople = foopeople::get_by_id( $foopeople_id );

				$post = array(
					'post_content' => $foopeople->shortcode(),
					'post_title'   => $foopeople->name,
					'post_status'  => 'draft',
					'post_type'    => 'page',
				);

				wp_insert_post( $post );
			}
			die();
		}

		public function ajax_clear_people_thumb_cache() {
			if ( check_admin_referer( 'foopeople_clear_people_thumb_cache', 'foopeople_clear_people_thumb_cache_nonce' ) ) {

				$foopeople_id = $_POST['foopeople_id'];

				$foopeople = foopeople::get_by_id( $foopeople_id );

				ob_start();

				//loop through all images, get the full sized file
				foreach ( $foopeople->attachments() as $attachment ) {
					$meta_data = wp_get_attachment_metadata( $attachment->ID );

					$file = $meta_data['file'];

					wpthumb_delete_cache_for_file( $file );
				}

				ob_end_clean();

				echo __( 'The thumbnail cache has been cleared!', FOOPEOPLE_SLUG );
			}

			die();
		}

		public function ajax_people_preview() {
			if ( check_admin_referer( 'foopeople_preview', 'foopeople_preview_nonce' ) ) {

				$foopeople_id = $_POST['foopeople_id'];

				$template = $_POST['foopeople_template'];

				//check that the template supports previews
				$people_template = foopeople_get_people_template( $template );
				if ( isset( $people_template['preview_support'] ) && true === $people_template['preview_support'] ) {

					global $foopeople_people_preview;

					$foopeople_people_preview = true;

					$args = array(
						'template'       => $template,
						'attachment_ids' => $_POST['foopeople_attachments'],
                        'preview'        => true
					);

					$args = apply_filters( 'foopeople_preview_arguments', $args, $_POST, $template );
					$args = apply_filters( 'foopeople_preview_arguments-' . $template, $args, $_POST );

					foopeople_render_people( $foopeople_id, $args );

					$foopeople_people_preview = false;

				} else {
					echo '<div style="padding:20px 50px 50px 50px; text-align: center">';
					echo '<h3>' . __( 'Preview not available!', FOOPEOPLE_SLUG ) . '</h3>';
					echo __('Sorry, but this people template does not support live previews. Please update the people in order to see what the people will look like.', FOOPEOPLE_SLUG );
					echo '</div>';
				}
			}

			die();
		}

		/**
		 * Handle people previews where there are no attachments
		 *
		 * @param $foopeople foopeople
		 */
		public function preview_no_attachments( $foopeople ) {
			global $foopeople_people_preview;

			if ( isset( $foopeople_people_preview ) && true === $foopeople_people_preview ) {
				$this->render_empty_people_preview();
			}
		}
	}
}
