<?php
/**
 * Created by bradvin
 * Date: 28/04/2017
 *
 */
if ( ! class_exists( 'PacePeople_MetaBox_Fields_Helper' ) ) {

	class PacePeople_Admin_People_MetaBox_Settings_Helper {

		private function __render_people_template_settings_tabs( $template, $sections ) {
			$tab_active = 'pacepeople-tab-active';
			foreach ( $sections as $section_slug => $section ) { ?>
				<div class="pacepeople-vertical-tab <?php echo $tab_active; ?>"
					 data-name="<?php echo $template['slug']; ?>-<?php echo $section_slug; ?>">
					<span class="dashicons <?php echo $section['icon_class']; ?>"></span>
					<span class="pacepeople-tab-text"><?php echo $section['name']; ?></span>
				</div>
				<?php
				$tab_active = '';
			}
		}

		private function render_people_template_settings_tab_contents( $template, $sections ) {
			$tab_active = 'pacepeople-tab-active';
			foreach ( $sections as $section_slug => $section ) { ?>
				<div class="pacepeople-tab-content <?php echo $tab_active; ?>"
					 data-name="<?php echo $template['slug']; ?>-<?php echo $section_slug; ?>">
					<?php $this->render_people_template_settings_tab_contents_fields( $template, $section ); ?>
				</div>
				<?php
				$tab_active = '';
			}
		}

		private function render_people_template_settings_tab_contents_fields( $template, $section ) {
			?>
			<table class="pacepeople-metabox-settings">
				<tbody>
				<?php
				foreach ( $section['fields'] as $field ) {
					$field_type = isset( $field['type'] ) ? $field['type'] : 'unknown';
					$field_class ="pacepeople_template_field pacepeople_template_field_type-{$field_type} pacepeople_template_field_id-{$field['id']} pacepeople_template_field_template-{$template['slug']} pacepeople_template_field_template_id-{$template['slug']}-{$field['id']}";
					$field_row_data_html = '';
					if ( isset( $field['row_data'] ) ) {
						$field_row_data = array_map( 'esc_attr', $field['row_data'] );
						foreach ( $field_row_data as $field_row_data_name => $field_row_data_value ) {
							$field_row_data_html .= " $field_row_data_name=" . '"' . $field_row_data_value . '"';
						}
					}
					?>
					<tr class="<?php echo $field_class; ?>"<?php echo $field_row_data_html; ?>>
						<?php if ( 'help' == $field_type ) { ?>
							<td colspan="2">
								<div class="pacepeople-help">
									<?php echo $field['desc']; ?>
								</div>
							</td>
						<?php } else { ?>
							<th>
								<label for="PacePeopleSettings_<?php echo $template['slug'] . '_' . $field['id']; ?>"><?php echo $field['title']; ?></label>
								<?php if ( !empty( $field['desc'] ) ) { ?>
									<span data-balloon-length="large" data-balloon-pos="right" data-balloon="<?php echo esc_attr($field['desc']); ?>"><i class="dashicons dashicons-editor-help"></i></span>
								<?php } ?>
							</th>
							<td>
								<?php do_action( 'pacepeople_render_people_template_field', $field, $this->people, $template ); ?>
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php
		}

		private function __render_people_template_settings( $template ) {
			$sections = $this->__build_model_for_template( $template );
			?>
			<div class="pacepeople-settings">
				<div class="pacepeople-vertical-tabs">
					<?php $this->__render_people_template_settings_tabs( $template, $sections ); ?>
				</div>
				<div class="pacepeople-tab-contents">
					<?php $this->render_people_template_settings_tab_contents( $template, $sections ); ?>
				</div>
			</div>
			<?php
		}

		public function __render_people_settings() {
			foreach ( $this->people_templates as $template ) {
				$field_visibility = ($this->current_people_template !== $template['slug']) ? 'style="display:none"' : '';
				?><div
				class="pacepeople-settings-container pacepeople-settings-container-<?php echo $template['slug']; ?>"
				<?php echo $field_visibility; ?>>
				<?php $this->__render_people_template_settings( $template ); ?>
				</div><?php
			}
		}

		/**
		 * build up and return a model that we can use to render the people settings
		 */
		private function __build_model_for_template($template) {

		    $fields = pacepeople_get_fields_for_template( $template );

			//create a sections array and fill it with fields
			$sections = array();
			foreach ( $fields as $field ) {

				if (isset($field['type']) && 'help' == $field['type'] && $this->hide_help) {
					continue; //skip help if the 'hide help' setting is turned on
				}

				$section_name = isset($field['section']) ? $field['section'] : __( 'General', 'pacepeople' );

				$section_slug = strtolower( $section_name );

				if ( !isset( $sections[ $section_slug ] ) ) {
					$sections[ $section_slug ] = array (
						'name' => $section_name,
						'icon_class' => apply_filters( 'pacepeople_people_settings_metabox_section_icon', $section_slug ),
						'fields' => array()
					);
				}

				$sections[ $section_slug ]['fields'][] = $field;
			}

			return $sections;
		}
	}
}