<?php

namespace FooPlugins\FooPeople\Admin\Metaboxes;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Metaboxes\FieldRenderer' ) ) {

	class FieldRenderer {

		/**
		 * Renders all tabs for metabox fields
		 *
		 * @param array $field_group
		 * @param string $id
		 * @param array $state
		 */
		static function render_tabs( $field_group, $id, $state ) {

			$classes[] = 'foometafields-container';
			$classes[] = 'foometafields-container-' . $id;

			?>
			<style>
				#
				<?php echo $id; ?>
				.inside {
					margin: 0;
				}
			</style>
		<div class="<?php echo implode( ' ', $classes ); ?>">
			<div>
				<div class="foometafields-tabs">
					<?php
					$tab_active = 'foometafields-active';
					foreach ( $field_group['tabs'] as $tab ) {
						$tab_type = isset( $tab['type'] ) ? $tab['type'] : 'normal';
						$taxonomy = '';
						if ( $tab_type === 'taxonomy' && isset( $tab['taxonomy'] ) ) {
							$taxonomy = ' data-taxonomy="';
							$taxonomy .= is_taxonomy_hierarchical( $tab['taxonomy'] ) ? 'taxonomy-' : '';
							$taxonomy .= $tab['taxonomy'];
							$taxonomy .= '"';
						}
						?>
						<div class="foometafields-tab <?php echo $tab_active; ?>"
							 data-name="<?php echo $id . '-' . $tab['id']; ?>"
								<?php echo $taxonomy ?>
						>
							<span class="dashicons <?php echo $tab['icon']; ?>"></span>
							<span class="foometafields-tab-text"><?php echo $tab['label']; ?></span>
						</div>
						<?php
						$tab_active = '';
					}
					?>
				</div>
				<div class="foometafields-contents">
					<?php
					$tab_active = 'foometafields-active';
					foreach ( $field_group['tabs'] as $tab ) {
						$featuredImage = '';
						if ( isset( $tab['featuredImage'] ) ) :
							$featuredImage = ' data-feature-image="true"';
							?>
							<style>
								#postimagediv {
									display: block !important;
								}

								#adv-settings label[for="postimagediv-hide"] {
									display: none !important;
								}
							</style>
						<?php endif; ?>

						<div class="foometafields-content <?php echo $tab_active; ?>"
							 data-name="<?php echo $id . '-' . $tab['id']; ?>"
								<?php echo $featuredImage ?>
						>

							<?php if ( isset( $tab['taxonomy'] ) ) :
								$panel = is_taxonomy_hierarchical( $tab['taxonomy'] ) ? $tab['taxonomy'] . 'div' : 'tagsdiv-' . $tab['taxonomy'];
								?>
								<style>
									/* Hide taxonomy boxes in sidebar and screen options show/hide checkbox labels */
									<?php echo '#'.$panel; ?>
									,
									#adv-settings label[for="<?php echo $panel ?>-hide"] {
										display: none !important;
									}

									#taxonomy-<?php echo esc_html( $tab['taxonomy'] ); ?> .category-tabs {
										display: none !important;
									}

									#taxonomy-<?php echo esc_html( $tab['taxonomy'] ); ?> .tabs-panel {
										border: none !important;
									}
								</style>
							<?php endif; ?>


							<?php self::render_fields( $tab['fields'], $id, $state ); ?>
						</div>
						<?php
						$tab_active = '';
					}
					?>
				</div>
			</div>
			</div><?php
		}

		/**
		 * Renders a group of metabox fields
		 *
		 * @param array $fields
		 * @param string $id
		 * @param array $state
		 */
		static function render_fields( $fields, $id, $state ) {
			?>
			<table>
				<tbody>
				<?php
				foreach ( $fields as $field ) {
					$field_type                     = isset( $field['type'] ) ? $field['type'] : 'unknown';
					$field_single_column            = isset( $field['single_column'] ) ? $field['single_column'] : false;
					$single_column_class            = '';
					$field_single_column_show_title = $field_single_column_show_desc = true;
					$field_classes                  = array();
					$field_classes[]                = 'foometafields-field';
					$field_classes[]                = "foometafields-field-{$field_type}";
					$field_classes[]                = "foometafields-field-{$field['id']}";
					if ( ! $field_single_column && isset( $field['class'] ) ) {
						$field_classes[] = $field['class'];
					}
					$field_row_data_html = '';
					if ( isset( $field['row_data'] ) ) {
						$field_row_data = array_map( 'esc_attr', $field['row_data'] );
						foreach ( $field_row_data as $field_row_data_name => $field_row_data_value ) {
							$field_row_data_html .= " $field_row_data_name=" . '"' . $field_row_data_value . '"';
						}
					}
					//get the value of the field from the state
					if ( is_array( $state ) && array_key_exists( $field['id'], $state ) ) {
						$field['value'] = $state[ $field['id'] ];
					}

					//check for any special non-editable field types
					if ( 'help' === $field_type ) {
						$field_single_column            = true;
						$single_column_class            = 'foometafields-single-column-icon foometafields-single-column-icon-help';
						$field_single_column_show_title = false;
						$field_single_column_show_desc  = true;
					} else if ( 'section' === $field_type ) {
						$field_single_column            = true;
						$field_single_column_show_title = true;
						$field_single_column_show_desc  = false;
					} else if ( 'singlecolumn' === $field_type ) {
						$field_single_column = true;
						$single_column_class = isset( $field['class'] ) ? $field['class'] : '';
					}
					?>
					<tr class="<?php echo implode( ' ', $field_classes ); ?>"<?php echo $field_row_data_html; ?>>
						<?php if ( $field_single_column ) { ?>
							<td colspan="2" class="foometafields-single-column">
								<?php if ( $field_single_column_show_title && isset( $field['title'] ) ) { ?>
									<h3 class="<?php echo esc_attr( $single_column_class ); ?>">
										<?php echo esc_html( $field['title'] ); ?>
									</h3>
								<?php } ?>
								<?php if ( $field_single_column_show_desc && isset( $field['desc'] ) ) { ?>
									<p class="<?php echo esc_attr( $single_column_class ); ?>">
										<?php echo esc_html( $field['desc'] ); ?>
									</p>
								<?php } ?>
							</td>
						<?php } else { ?>
							<th>
								<label for="fooplugins_metabox_field_<?php echo $id . '_' . $field['id']; ?>"><?php echo $field['title']; ?></label>
								<?php if ( ! empty( $field['tooltip'] ) ) { ?>
									<span data-balloon-length="large" data-balloon-pos="right"
										  data-balloon="<?php echo esc_attr( $field['desc'] ); ?>">
										<i class="dashicons dashicons-editor-help"></i>
									</span>
								<?php } ?>
							</th>
							<td>
								<?php self::render_field( $field, $id ); ?>
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php
		}

		/**
		 * Renders a single metabox field
		 *
		 * @param array $field
		 * @param string $meta_key
		 */
		static function render_field( $field, $meta_key ) {
			//only declare up front so no debug warnings are shown
			$type = $id = $desc = $default = $placeholder = $choices = $class = $spacer = null;

			extract( $field );

			$type = sanitize_title( isset( $field['type'] ) ? $field['type'] : 'text' );

			$field_class = empty( $class ) ? '' : ' class="' . esc_attr( $class ) . '"';

			if ( ! isset( $field['value'] ) ) {
				$field['value'] = '';
			}

			$input_id   = 'fooplugins_metabox_field_' . $meta_key . '_' . $id;
			$input_name = $meta_key . '[' . $id . ']';

			echo '<div class="foometafields-field-input-' . esc_attr( $type ) . '">';

			switch ( $type ) {

				case 'html':
					echo $desc;
					$desc = '';
					break;

				case 'checkbox':

					$checked = 'on' === $field['value'] ? ' checked="checked"' : '';
					echo '<input' . $field_class . ' type="checkbox" id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $input_name ) . '" value="on"' . $checked . ' />';
					break;

				case 'select':
					echo '<select' . $field_class . ' id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $input_name ) . '">';
					foreach ( $choices as $value => $label ) {
						$selected = '';
						if ( $field['value'] == $value ) {
							$selected = ' selected="selected"';
						}
						echo '<option ' . $selected . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
					}

					echo '</select>';
					break;

				case 'radio':
					$i      = 0;
					$spacer = isset( $spacer ) ? $spacer : '<br />';
					foreach ( $choices as $value => $label ) {
						$selected = '';
						if ( $field['value'] == $value ) {
							$selected = ' checked="checked"';
						}
						echo '<input' . $field_class . $selected . ' type="radio" name="' . esc_attr( $input_name ) . '"  id="' . esc_attr( $input_id . $i ) . '" value="' . esc_attr( $value ) . '"> <label for="' . esc_attr( $input_id . $i ) . '">' . esc_html( $label ) . '</label>';
						if ( $i < count( $choices ) - 1 ) {
							echo $spacer;
						}
						$i ++;
					}
					break;

				case 'textarea':
					echo '<textarea' . $field_class . ' id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $input_name ) . '" placeholder="' . esc_attr( $placeholder ) . '">' . esc_textarea( $field['value'] ) . '</textarea>';

					break;

				case 'text':
					echo '<input' . $field_class . ' type="text" id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $input_name ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $placeholder ) . '"/>';

					break;

				case 'suggest':
					$action = isset( $field['action'] ) ? $field['action'] : 'foometafield_suggest';
					$query  = build_query( array(
							'action'     => $action,
							'nonce'      => wp_create_nonce( $action ),
							'query_type' => isset( $field['query_type'] ) ? $field['query_type'] : 'post',
							'query_data' => isset( $field['query_data'] ) ? $field['query_data'] : 'page'
					) );

					self::render_html_tag( 'input', array(
							'type'                   => 'text',
							'id'                     => $input_id,
							'name'                   => $input_name,
							'value'                  => $field['value'],
							'placeholder'            => $placeholder,
							'data-suggest',
							'data-suggest-query'     => $query,
							'data-suggest-multiple'  => isset( $field['multiple'] ) ? $field['multiple'] : 'false',
							'data-suggest-separator' => isset( $field['separator'] ) ? $field['separator'] : ','
					) );

					break;

				case 'selectize':
					$action = isset( $field['action'] ) ? $field['action'] : 'foometafield_selectize';
					$query  = build_query( array(
							'action'     => $action,
							'nonce'      => wp_create_nonce( $action ),
							'query_type' => isset( $field['query_type'] ) ? $field['query_type'] : 'post',
							'query_data' => isset( $field['query_data'] ) ? $field['query_data'] : 'page'
					) );

					$value = ( isset( $field['value'] ) && is_array( $field['value'] ) ) ? $field['value'] : array(
							'value'   => '',
							'display' => ''
					);

					self::render_html_tag( 'input', array(
							'type'  => 'hidden',
							'id'    => $input_id . '_display',
							'name'  => $input_name . '[display]',
							'value' => $value['display']
					) );

					$inner = '';

					if ( isset( $value['value'] ) ) {
						$inner = '<option value="' . esc_attr( $value['value'] ) . '" selected="selected">' . esc_html( $value['display'] ) . '</option>';
					}

					self::render_html_tag( 'select', array(
							'id'                     => $input_id,
							'name'                   => $input_name . '[value]',
							'value'                  => $value['value'],
							'placeholder'            => $placeholder,
							'data-selectize-instance',
							'data-selectize-query'   => $query,
							'data-selectize-display' => $input_id . '_display'
					), $inner, true, false );

					break;

				case 'select2':
					$action = isset( $field['action'] ) ? $field['action'] : 'foometafield_select2';
					$inner  = '';

					self::render_html_tag( 'select', array(
							'id'                      => $input_id,
							'name'                    => $input_name,
							'value'                   => $field['value'],
							'placeholder'             => $placeholder,
							'data-select2-instance',
							'data-select2-action'     => $action,
							'data-select2-nonce'      => wp_create_nonce( $action ),
							'data-select2-query-type' => isset( $field['query_type'] ) ? $field['query_type'] : 'post',
							'data-select2-query-data' => isset( $field['query_data'] ) ? $field['query_data'] : 'page',
					), $inner );

					break;

				case 'color':

					self::render_html_tag( 'input', array(
							'type'  => 'color',
							'id'    => $input_id,
							'name'  => $input_name,
							'value' => $field['value']
					) );

					break;

				case 'colorpicker':

					self::render_html_tag( 'input', array(
							'type'  => 'text',
							'id'    => $input_id,
							'name'  => $input_name,
							'data-wp-color-picker',
							'value' => $field['value']
					) );

					break;


				case 'number':
					$min  = isset( $min ) ? $min : 0;
					$step = isset( $step ) ? $step : 1;
					echo '<input class="regular-text ' . esc_attr( $class ) . '" type="number" step="' . esc_attr( $step ) . '" min="' . esc_attr( $min ) . '" id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $input_name ) . '" placeholder="' . esc_attr( $placeholder ) . '" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'checkboxlist':
					$i = 0;
					foreach ( $choices as $value => $label ) {

						$checked = '';
						if ( isset( $field['value'][ $value ] ) && $field['value'][ $value ] == $value ) {
							$checked = 'checked="checked"';
						}

						echo '<input' . $field_class . ' ' . $checked . ' type="checkbox" name="' . esc_attr( $input_name ) . '[' . esc_attr( $value ) . ']" id="' . esc_attr( $input_id . $i ) . '" value="' . esc_attr( $value ) . '" data-value="' . esc_attr( $value ) . '"> <label for="' . esc_attr( $input_id . $i ) . '">' . esc_html( $label ) . '</label>';
						if ( $i < count( $choices ) - 1 ) {
							echo '<br />';
						}
						$i ++;
					}

					break;

				case 'htmllist':
					$i          = 0;
					$input_name = $meta_key . '[' . $id . ']';
					$icon_html  = '';
					foreach ( $choices as $value => $item ) {
						$selected  = ( $field['value'] == $value ) ? ' checked="checked"' : '';
						$icon_html .= '<input style="display:none" name="' . esc_attr( $input_name ) . '" id="' . esc_attr( $input_id . $i ) . '" ' . $selected . ' type="radio" value="' . esc_attr( $value ) . '" tabindex="' . $i . '"/>';
						$title     = $item['label'];
						$html      = wp_kses_post( $item['html'] );
						$icon_html .= '<label for="' . esc_attr( $input_id . $i ) . '" data-balloon-length="small" data-balloon-pos="down" data-balloon="' . esc_attr( $title ) . '">' . $html . '</label>';
						$i ++;
					}
					echo $icon_html;
					break;

				default:
					//the field type is not natively supported
					if ( isset( $field['function'] ) ) {
						call_user_func( $field['function'], $field, $meta_key );
					}
					break;
			}

			echo '</div>';

			if ( ! empty( $field['desc'] ) ) {
				self::render_html_tag( 'span', array(
						'class' => 'foometafields-field-description'
				), $field['desc'] );
			}
		}

		/**
		 * Safely renders an HTML tag
		 *
		 * @param $tag
		 * @param $attributes
		 * @param string $inner
		 * @param bool $close
		 * @param bool $escape_inner
		 */
		static function render_html_tag( $tag, $attributes, $inner = '', $close = true, $escape_inner = true ) {
			echo '<' . $tag . ' ';
			//make sure all attributes are escaped
			$attributes     = array_map( 'esc_attr', $attributes );
			$attributePairs = [];
			foreach ( $attributes as $key => $val ) {
				if ( is_int( $key ) ) {
					$attributePairs[] = esc_attr( $val );
				} else {
					$val              = esc_attr( $val );
					$attributePairs[] = "{$key}=\"{$val}\"";
				}
			}
			echo implode( ' ', $attributePairs );
			echo '>';
			if ( isset( $inner ) ) {
				if ( $escape_inner ) {
					echo esc_html( $inner );
				} else {
					echo $inner;
				}
			}
			if ( $close ) {
				echo '</' . $tag . '>';
			}
		}
	}
}
