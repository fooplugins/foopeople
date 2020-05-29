<?php
namespace FooPlugins\FooPeople\Admin;

/*
 * PacePeople Admin People MetaBoxes class
 */

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person_Metaboxes' ) ) {

	class Person_Metaboxes {

		public function __construct() {
			//add the metaboxes for a person
			add_action( 'add_meta_boxes_' . FOOPEOPLE_CPT_PERSON, array( $this, 'add_meta_boxes' ) );

			//enqueue assets needed for field groups
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			//save extra post data for a person
			add_action( 'save_post', array( $this, 'save_person' ) );
//
//			//save custom field on a page or post
//			add_Action( 'save_post', array( $this, 'attach_people_to_post' ), 10, 2 );
//
//			//whitelist metaboxes for our people postype
//			add_filter( 'pacepeople_metabox_sanity', array( $this, 'whitelist_metaboxes' ) );
//
//			//add scripts used by metaboxes
//			add_action( 'admin_enqueue_scripts', array( $this, 'include_required_scripts' ) );
//
//			// Ajax calls for creating a page for the people
//			add_action( 'wp_ajax_pacepeople_create_people_page', array( $this, 'ajax_create_people_page' ) );
//
//			// Ajax call for clearing thumb cache for the people
//			add_action( 'wp_ajax_pacepeople_clear_people_thumb_cache', array( $this, 'ajax_clear_people_thumb_cache' ) );
//
//			// Ajax call for generating a people preview
//			add_action( 'wp_ajax_pacepeople_preview', array( $this, 'ajax_people_preview' ) );
//
//			//handle previews that have no attachments
//			add_action( 'pacepeople_template_no_attachments', array( $this, 'preview_no_attachments' ) );
		}

		/**
		 * Add metaboxes to the person CPT
		 * @param $post
		 */
		public function add_meta_boxes( $post ) {
			add_meta_box(
				FOOPEOPLE_CPT_PERSON . '_details',
				__( 'Person Details', 'pacepeople' ),
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
		function enqueue_assets( $hook_suffix ){
			if( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
				$screen = get_current_screen();

				if ( is_object( $screen ) && FOOPEOPLE_CPT_PERSON == $screen->post_type ){
					Metabox_Field_Group::enqueue_assets();
				}
			}
		}

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

				do_action( PACEPEOPLE_ACTION_ADMIN_PERSON_BEFORE_SAVE, $post_id, $_POST );

				//save the person details
				$details = isset( $_POST[PACEPEOPLE_PERSON_META_DETAILS] ) ? $_POST[PACEPEOPLE_PERSON_META_DETAILS] : array();
                update_post_meta( $post_id, PACEPEOPLE_PERSON_META_DETAILS, $details );

                //update the search index after a person is saved
                $person = Person::get_by_id( $post_id );
                $search_index = $person->build_search_index();
                update_post_meta( $post_id, PACEPEOPLE_PERSON_META_SEARCH, $search_index );

				do_action( PACEPEOPLE_ACTION_ADMIN_PERSON_AFTER_SAVE, $post_id, $_POST );
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

                do_action( 'pacepeople_start_attach_people_to_post', $post_id );

				//Clear any pacepeople usages that the post might have
				delete_post_meta( $post_id, PACEPEOPLE_META_POST_USAGE );

				//get all pacepeople shortcodes that are on the page/post
				$people_shortcodes = pacepeople_extract_people_shortcodes( $post->post_content );

                if ( is_array( $people_shortcodes ) && count( $people_shortcodes ) > 0 ) {

                    foreach ( $people_shortcodes as $id => $shortcode ) {
                        //if the content contains the pacepeople shortcode then add a custom field
                        add_post_meta( $post_id, PACEPEOPLE_META_POST_USAGE, $id, false );

                        do_action( 'pacepeople_attach_people_to_post', $post_id, $id );
                    }
                }
			}
		}



		public function render_people_shortcode_metabox( $post ) {
			$people = $this->get_people( $post );
			$shortcode = $people->shortcode();
			?>
			<p class="pacepeople-shortcode">
				<input type="text" id="pacepeople-copy-shortcode" size="<?php echo strlen( $shortcode ) + 2; ?>" value="<?php echo htmlspecialchars( $shortcode ); ?>" readonly="readonly" />
			</p>
			<p>
				<?php _e( 'Paste the above shortcode into a post or page to show the people.', 'pacepeople' ); ?>
			</p>
			<script>
				jQuery(function($) {
					var shortcodeInput = document.querySelector('#pacepeople-copy-shortcode');
					shortcodeInput.addEventListener('click', function () {
						try {
							// select the contents
							shortcodeInput.select();
							//copy the selection
							document.execCommand('copy');
							//show the copied message
							$('.pacepeople-shortcode-message').remove();
							$(shortcodeInput).after('<p class="pacepeople-shortcode-message"><?php _e( 'Shortcode copied to clipboard :)','pacepeople' ); ?></p>');
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
					<?php _e( 'This people is used on the following posts or pages:', 'pacepeople' ); ?>
				</p>
				<ul class="ul-disc">
				<?php foreach ( $posts as $post ) {
					$url = get_permalink( $post->ID );
					echo '<li>' . $post->post_title . '&nbsp;';
					edit_post_link( __( 'Edit', 'pacepeople' ), '<span class="edit">', ' | </span>', $post->ID );
					echo '<span class="view"><a href="' . esc_url( $url ) . '" target="_blank">' . __( 'View', 'pacepeople' ) . '</a></li>';
				} ?>
				</ul>
			<?php } else { ?>
				<p>
					<?php _e( 'This people is not used on any pages or pages yet. Quickly create a page:', 'pacepeople' ); ?>
				</p>
				<div class="pacepeople_metabox_actions">
					<button class="button button-primary button-large" id="pacepeople_create_page"><?php _e( 'Create People Page', 'pacepeople' ); ?></button>
					<span id="pacepeople_create_page_spinner" class="spinner"></span>
					<?php wp_nonce_field( 'pacepeople_create_people_page', 'pacepeople_create_people_page_nonce', false ); ?>
				</div>
				<p>
					<?php _e( 'A draft page will be created which includes the people shortcode in the content. The title of the page will be the same title as the people.', 'pacepeople' ); ?>
				</p>
			<?php }
		}

		public function render_sorting_metabox( $post ) {
			$people = $this->get_people( $post );
			$sorting_options = pacepeople_sorting_options();
			if ( empty( $people->sorting ) ) {
				$people->sorting = '';
			}
			?>
			<p>
				<?php _e('Change the way images are sorted within your people. By default, they are sorted in the order you see them.', 'pacepeople'); ?>
			</p>
			<?php
			foreach ( $sorting_options as $sorting_key => $sorting_label ) { ?>
				<p>
				<input type="radio" value="<?php echo $sorting_key; ?>" <?php checked( $sorting_key === $people->sorting ); ?> id="PacePeopleSettings_PeopleSort_<?php echo $sorting_key; ?>" name="<?php echo PACEPEOPLE_META_SORT; ?>" />
				<label for="PacePeopleSettings_PeopleSort_<?php echo $sorting_key; ?>"><?php echo $sorting_label; ?></label>
				</p><?php
			} ?>
			<p class="pacepeople-help">
				<?php _e('PLEASE NOTE : sorting randomly will force HTML Caching for the people to be disabled.', 'pacepeople'); ?>
			</p>
			<?php
		}

		public function render_retina_metabox( $post ) {
			$people = $this->get_people( $post );
			$retina_options = pacepeople_retina_options();
			if ( empty( $people->retina ) ) {
				$people->retina = pacepeople_get_setting( 'default_retina_support', array() );
			}
			?>
			<p>
				<?php _e('Add retina support to this people by choosing the different pixel densities you want to enable.', 'pacepeople'); ?>
			</p>
			<?php
			foreach ( $retina_options as $retina_key => $retina_label ) {
				$checked = array_key_exists( $retina_key, $people->retina ) ? ('true' === $people->retina[$retina_key]) : false;
				?>
				<p>
				<input type="checkbox" value="true" <?php checked( $checked ); ?> id="PacePeopleSettings_Retina_<?php echo $retina_key; ?>" name="<?php echo PACEPEOPLE_META_RETINA; ?>[<?php echo $retina_key; ?>]" />
				<label for="PacePeopleSettings_Retina_<?php echo $retina_key; ?>"><?php echo $retina_label; ?></label>
				</p><?php
			} ?>
			<p class="pacepeople-help">
				<?php _e('PLEASE NOTE : thumbnails will be generated for each of the pixel densities chosen, which will increase your website\'s storage space!', 'pacepeople'); ?>
			</p>
			<?php
		}

		public function render_thumb_settings_metabox( $post ) {
			$people = $this->get_people( $post );
			$force_use_original_thumbs = get_post_meta( $post->ID, PACEPEOPLE_META_FORCE_ORIGINAL_THUMBS, true );
			$checked = 'true' === $force_use_original_thumbs; ?>
			<p>
				<?php _e( 'Clear all the previously cached thumbnails that have been generated for this people.', 'pacepeople' ); ?>
			</p>
			<div class="pacepeople_metabox_actions">
				<button class="button button-primary button-large" id="pacepeople_clear_thumb_cache"><?php _e( 'Clear Thumbnail Cache', 'pacepeople' ); ?></button>
				<span id="pacepeople_clear_thumb_cache_spinner" class="spinner"></span>
				<?php wp_nonce_field( 'pacepeople_clear_people_thumb_cache', 'pacepeople_clear_people_thumb_cache_nonce', false ); ?>
			</div>
			<p>
				<input type="checkbox" value="true" <?php checked( $checked ); ?> id="PacePeopleSettings_ForceOriginalThumbs" name="<?php echo PACEPEOPLE_META_FORCE_ORIGINAL_THUMBS; ?>" />
				<label for="PacePeopleSettings_ForceOriginalThumbs"><?php _e('Force Original Thumbs', 'pacepeople'); ?></label>
			</p>
			<?php
		}

		public function include_required_scripts() {
			$screen_id = foo_current_screen_id();

			//only include scripts if we on the pacepeople add/edit page
			if ( FOOPEOPLE_CPT_PERSON === $screen_id ||
			     'edit-' . FOOPEOPLE_CPT_PERSON === $screen_id ) {

				//enqueue any dependencies from extensions or people templates
				do_action( 'pacepeople_enqueue_preview_dependencies' );
				//add core pacepeople files for preview
				pacepeople_enqueue_core_people_template_style();
				pacepeople_enqueue_core_people_template_script();

				//spectrum needed for the colorpicker field
				$url = FOOPEOPLE_URL . 'lib/spectrum/spectrum.js';
				wp_enqueue_script( 'pacepeople-spectrum', $url, array('jquery'), FOOPEOPLE_VERSION );
				$url = FOOPEOPLE_URL . 'lib/spectrum/spectrum.css';
				wp_enqueue_style( 'pacepeople-spectrum', $url, array(), FOOPEOPLE_VERSION );

				//include any admin js required for the templates
				foreach ( pacepeople_people_templates() as $template ) {
					$admin_js = foo_safe_get( $template, 'admin_js' );
					if ( is_array( $admin_js ) ) {
						//dealing with an array of js files to include
						foreach( $admin_js as $admin_js_key => $admin_js_src ) {
							wp_enqueue_script( 'pacepeople-people-admin-' . $template['slug'] . '-' . $admin_js_key, $admin_js_src, array('jquery', 'media-upload', 'jquery-ui-sortable'), FOOPEOPLE_VERSION );
						}
					} else {
						//dealing with a single js file to include
						wp_enqueue_script( 'pacepeople-people-admin-' . $template['slug'], $admin_js, array('jquery', 'media-upload', 'jquery-ui-sortable'), FOOPEOPLE_VERSION );
					}
				}
			}
		}

		public function render_customcss_metabox( $post ) {
			$people = $this->get_people( $post );
			$custom_css = $people->custom_css;
			$example = '<code>#pacepeople-people-' . $post->ID . ' { }</code>';
			?>
			<p>
				<?php printf( __( 'Add any custom CSS to target this specific people. For example %s', 'pacepeople' ), $example ); ?>
			</p>
			<table id="table_styling" class="form-table">
				<tbody>
				<tr>
					<td>
						<textarea class="pacepeople_metabox_custom_css" name="<?php echo PACEPEOPLE_META_CUSTOM_CSS; ?>" type="text"><?php echo $custom_css; ?></textarea>
					</td>
				</tr>
				</tbody>
			</table>
			<?php
		}

		public function ajax_create_people_page() {
			if ( check_admin_referer( 'pacepeople_create_people_page', 'pacepeople_create_people_page_nonce' ) ) {

				$pacepeople_id = $_POST['pacepeople_id'];

				$pacepeople = PacePeople::get_by_id( $pacepeople_id );

				$post = array(
					'post_content' => $pacepeople->shortcode(),
					'post_title'   => $pacepeople->name,
					'post_status'  => 'draft',
					'post_type'    => 'page',
				);

				wp_insert_post( $post );
			}
			die();
		}

		public function ajax_clear_people_thumb_cache() {
			if ( check_admin_referer( 'pacepeople_clear_people_thumb_cache', 'pacepeople_clear_people_thumb_cache_nonce' ) ) {

				$pacepeople_id = $_POST['pacepeople_id'];

				$pacepeople = PacePeople::get_by_id( $pacepeople_id );

				ob_start();

				//loop through all images, get the full sized file
				foreach ( $pacepeople->attachments() as $attachment ) {
					$meta_data = wp_get_attachment_metadata( $attachment->ID );

					$file = $meta_data['file'];

					wpthumb_delete_cache_for_file( $file );
				}

				ob_end_clean();

				echo __( 'The thumbnail cache has been cleared!', 'pacepeople' );
			}

			die();
		}

		public function ajax_people_preview() {
			if ( check_admin_referer( 'pacepeople_preview', 'pacepeople_preview_nonce' ) ) {

				$pacepeople_id = $_POST['pacepeople_id'];

				$template = $_POST['pacepeople_template'];

				//check that the template supports previews
				$people_template = pacepeople_get_people_template( $template );
				if ( isset( $people_template['preview_support'] ) && true === $people_template['preview_support'] ) {

					global $pacepeople_people_preview;

					$pacepeople_people_preview = true;

					$args = array(
						'template'       => $template,
						'attachment_ids' => $_POST['pacepeople_attachments'],
                        'preview'        => true
					);

					$args = apply_filters( 'pacepeople_preview_arguments', $args, $_POST, $template );
					$args = apply_filters( 'pacepeople_preview_arguments-' . $template, $args, $_POST );

					pacepeople_render_people( $pacepeople_id, $args );

					$pacepeople_people_preview = false;

				} else {
					echo '<div style="padding:20px 50px 50px 50px; text-align: center">';
					echo '<h3>' . __( 'Preview not available!', 'pacepeople' ) . '</h3>';
					echo __('Sorry, but this people template does not support live previews. Please update the people in order to see what the people will look like.', 'pacepeople' );
					echo '</div>';
				}
			}

			die();
		}

		/**
		 * Handle people previews where there are no attachments
		 *
		 * @param $pacepeople PacePeople
		 */
		public function preview_no_attachments( $pacepeople ) {
			global $pacepeople_people_preview;

			if ( isset( $pacepeople_people_preview ) && true === $pacepeople_people_preview ) {
				$this->render_empty_people_preview();
			}
		}
	}
}
