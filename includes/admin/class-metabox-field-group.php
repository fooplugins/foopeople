<?php

namespace FooPlugins\FooPeople\Admin;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Metabox_Field_Group' ) ) {

    class Metabox_Field_Group {

    	static function render_field_group( $field_group ) {
			?>
			<style>
				#<?php echo $field_group['container_id']; ?> .inside {
					margin: 0;
					padding: 0;
				}
			</style>
			<div class="foopeople-fields-container foopeople-fields-container-<?php echo $field_group['slug']; ?>">
				<div class="foopeople-settings">
					<div class="foopeople-vertical-tabs">
						<?php
						$tab_active = 'foopeople-tab-active';
						foreach ( $field_group['sections'] as $section ) { ?>
							<div class="foopeople-vertical-tab <?php echo $tab_active; ?>" data-name="<?php echo $field_group['slug']; ?>-<?php echo $section['slug']; ?>">
								<span class="dashicons <?php echo $section['icon']; ?>"></span>
								<span class="foopeople-tab-text"><?php echo $section['label']; ?></span>
							</div>
							<?php
							$tab_active = '';
						}
						?>
					</div>
					<div class="foopeople-tab-contents">
						<?php
						$tab_active = 'foopeople-tab-active';
						foreach ( $field_group['sections'] as $section ) { ?>
							<div class="foopeople-tab-content <?php echo $tab_active; ?>"
								 data-name="<?php echo $field_group['slug']; ?>-<?php echo $section['slug']; ?>">
								<?php self::render_field_section( $section, $field_group['slug'] ); ?>
							</div>
							<?php
							$tab_active = '';
						}
						?>
					</div>
				</div>
			</div><?php
		}

		static function render_field_section( $section, $group_name ) {
			?>
			<table class="foopeople-metabox-settings">
				<tbody>
				<?php
				foreach ( $section['fields'] as $field ) {
					$field_type = isset( $field['type'] ) ? $field['type'] : 'unknown';
					$field_class ="foopeople_metabox_field foopeople_metabox_field_type-{$field_type} foopeople_metabox_field_id-{$field['id']} foopeople_metabox_field_group-{$group_name}";
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
								<div class="foopeople-help">
									<?php echo $field['desc']; ?>
								</div>
							</td>
						<?php } else { ?>
							<th>
								<label for="foopeopleSettings_<?php echo $group_name . '_' . $field['id']; ?>"><?php echo $field['title']; ?></label>
								<?php if ( !empty( $field['desc'] ) ) { ?>
									<span data-balloon-length="large" data-balloon-pos="right" data-balloon="<?php echo esc_attr($field['desc']); ?>"><i class="dashicons dashicons-editor-help"></i></span>
								<?php } ?>
							</th>
							<td>
								<?php Metabox_Field::render( $field, $group_name ); ?>
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php
		}

        /***
         * Enqueue any assets needed by field groups
         */
        // static function enqueue_assets(){
		// 	// Register, enqueue scripts and styles here
		// 	wp_enqueue_script( 'foopeople-metabox-field-groups', FOOPEOPLE_URL . '/assets/js/metabox-field-groups.min.js', array('jquery'), FOOPEOPLE_VERSION );
		// 	wp_enqueue_style( 'foopeople-metabox-field-groups', FOOPEOPLE_URL . '/assets/css/metabox-field-groups.min.css', array(), FOOPEOPLE_VERSION );
        // }

        static function extract_data_from_post( $field_group, $form_data ) {

		}
    }
}