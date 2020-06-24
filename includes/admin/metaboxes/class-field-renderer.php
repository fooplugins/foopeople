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
				#<?php echo $id; ?> .inside {
					margin: 0;
				}
			</style>
		<div class="<?php echo implode( ' ', $classes ); ?>">
			<div>
				<div class="foometafields-tabs">
					<?php
					$tab_active = 'foometafields-active';
					foreach ( $field_group['tabs'] as $tab ) {
						self::render_tab( $tab, $id, $tab_active );
						$tab_active = '';
					}
					?>
				</div>
				<div class="foometafields-contents">
					<?php
					$tab_active = 'foometafields-active';
					foreach ( $field_group['tabs'] as $tab ) {
						self::render_tab_content( $tab, $id, $tab_active, $state );
						$tab_active = '';
					}
					?>
				</div>
			</div>
			</div><?php
		}

		/**
		 * Renders a tab
		 *
		 * @param $tab
		 * @param $container_id
		 * @param $tab_active
		 * @param string $tab_class
		 */
		static function render_tab( $tab, $container_id, $tab_active, $tab_class = 'foometafields-tab' ) {
			$tab_type = isset( $tab['type'] ) ? $tab['type'] : 'normal';
			$taxonomy = '';
			if ( $tab_type === 'taxonomy' && isset( $tab['taxonomy'] ) ) {
				$taxonomy = ' data-taxonomy="';
				$taxonomy .= is_taxonomy_hierarchical( $tab['taxonomy'] ) ? 'taxonomy-' : '';
				$taxonomy .= $tab['taxonomy'];
				$taxonomy .= '"';
			}

			$tab_id = $tab['id'];

			if ( !isset( $tab['fields'] ) && isset( $tab['tabs'] ) ) {
				//set the tab_id to be the first child tab
				$tab_id = $tab['tabs'][0]['id'];
			}
			?>
			<div class="<?php echo $tab_class; ?> <?php echo $tab_active; ?>"
				 data-name="<?php echo $container_id . '-' . $tab_id; ?>"
					<?php echo $taxonomy ?>
			>
				<?php if ( isset( $tab['icon'] ) ) { ?>
				<span class="dashicons <?php echo $tab['icon']; ?>"></span>
				<?php } ?>
				<span class="foometafields-tab-text"><?php echo $tab['label']; ?></span>
				<?php
				if ( isset( $tab['tabs'] ) ) {
					echo '<div class="foometafields-child-tabs">';
					foreach ( $tab['tabs'] as $child_tab ) {
						self::render_tab( $child_tab, $container_id, $tab_active, 'foometafields-child-tab' );
					}
					echo '</div>';
				}
				?>
			</div>
			<?php

		}

		/**
		 * Renders the tab content
		 *
		 * @param $tab
		 * @param $container_id
		 * @param $tab_active
		 * @param $state
		 */
		static function render_tab_content( $tab, $container_id, $tab_active, $state ) {
			$featuredImage = '';
			if ( isset( $tab['featuredImage'] ) ) {
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
			<?php } ?>

			<div class="foometafields-content <?php echo $tab_active; ?>"
				 data-name="<?php echo $container_id . '-' . $tab['id']; ?>"
					<?php echo $featuredImage ?>
			>

				<?php if ( isset( $tab['taxonomy'] ) ) {
					$panel = is_taxonomy_hierarchical( $tab['taxonomy'] ) ? $tab['taxonomy'] . 'div' : 'tagsdiv-' . $tab['taxonomy'];
					?>
					<style>
						/* Hide taxonomy boxes in sidebar and screen options show/hide checkbox labels */
						#<?php echo $panel; ?>,
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
				<?php } ?>

				<?php
				if ( isset( $tab['fields'] ) ) {
					self::render_fields( $tab['fields'], $container_id, $state );
				}
				?>
			</div>
			<?php
			if ( isset( $tab['tabs'] ) ) {
				foreach ( $tab['tabs'] as $tab ) {
					self::render_tab_content( $tab, $container_id, '', $state );
				}
			}
		}

		/**
		 * Renders a group of metabox fields
		 *
		 * @param array $fields
		 * @param string $id
		 * @param array $state
		 */
		static function render_fields( $fields, $id, $state ) {
			foreach ( $fields as $field ) {
				$field['input_id']				= "foometafields_{$id}_{$field['id']}";
				$field['input_name']		 	= "{$id}[{$field['id']}]";
				$field_type                     = isset( $field['type'] ) ? $field['type'] : 'unknown';
				$field_layout                   = isset( $field['layout'] ) ? $field['layout'] : 'block';
				$field_classes                  = array();
				$field_classes[]                = 'foometafields-field';
				$field_classes[]                = "foometafields-type-{$field_type}";
				$field_classes[]                = "foometafields-id-{$field['id']}";
				$field_classes[]				= "foometafields-layout-{$field_layout}";
				if ( isset( $field['class'] ) ) {
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
					$field['type'] = 'html';
					$field_classes[] = 'foometafields-icon foometafields-icon-help';
					$field['desc'] = '<p>' . esc_html( $field['desc'] ) . '</p>';
				} else if ( 'heading' === $field_type ) {
					$field['type'] = 'html';
					$field['desc'] = '<h3>' . esc_html( $field['desc'] ) . '</h3>';
				}
				?>
				<div class="<?php echo implode( ' ', $field_classes ); ?>"<?php echo $field_row_data_html; ?>>
					<?php if ( isset( $field['label'] ) ) { ?>
						<div class="foometafields-label">
							<label for="foometafields_<?php echo $id . '_' . $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
							<?php if ( ! empty( $field['tooltip'] ) ) { ?>
								<span data-balloon-length="large" data-balloon-pos="right"
									  data-balloon="<?php echo esc_attr( $field['desc'] ); ?>">
									<i class="dashicons dashicons-editor-help"></i>
								</span>
							<?php } ?>
						</div>
					<?php }
						self::render_field( $field );
				 	?>
				</div>
			<?php }
		}

		/**
		 * Renders a single metabox field
		 *
		 * @param array $field
		 * @param array $field_attributes
		 */
		static function render_field( $field, $field_attributes = array() ) {
			$type = sanitize_title( isset( $field['type'] ) ? $field['type'] : 'text' );

			$attributes = array(
				'id'   => $field['input_id'],
				'name' => $field['input_name']
			);

			//set a default value if nothing is set
			if ( ! isset( $field['value'] ) ) {
				$field['value'] = '';
			}

			//merge the attributes with any that are passed in
			$attributes = wp_parse_args( $field_attributes, $attributes );

			echo '<div class="foometafields-field-input foometafields-field-input-' . esc_attr( $type ) . '">';

			switch ( $type ) {

				case 'html':
					if ( isset( $field['desc'] ) ) {
						echo $field['desc'];
						$field['desc'] = '';
					} else if ( isset( $field['html'] ) ) {
						echo $field['html'];
					}
					break;

				case 'select':
					self::render_html_tag( 'select', $attributes, null, false );
					foreach ( $field['choices'] as $value => $label ) {
						$option_attributes = array(
							'value' => $value
						);
						if ( $field['value'] == $value ) {
							$option_attributes['selected'] = 'selected';
						}
						self::render_html_tag( 'option', $option_attributes, $label );
					}
					echo '</select>';

					break;

				case 'textarea':
					if ( isset( $field['placeholder'] ) ) {
						$attributes['placeholder'] = $field['placeholder'];
					}
					self::render_html_tag( 'textarea', $attributes, esc_textarea( $field['value'] ), true, false );

					break;

				case 'text':
					if ( isset( $field['placeholder'] ) ) {
						$attributes['placeholder'] = $field['placeholder'];
					}
					$attributes['type'] = 'text';
					$attributes['value'] = $field['value'];
					self::render_html_tag( 'input', $attributes );

					break;

				case 'number':
					if ( isset( $field['placeholder'] ) ) {
						$attributes['placeholder'] = $field['placeholder'];
					}
					$attributes['min'] = isset( $min ) ? $min : 0;
					$attributes['step'] = isset( $step ) ? $step : 1;
					$attributes['type'] = 'number';
					$attributes['value'] = $field['value'];
					self::render_html_tag( 'input', $attributes );

					break;

				case 'color':
					$attributes['type'] = 'color';
					$attributes['value'] = $field['value'];
					self::render_html_tag( 'input', $attributes );

					break;

				case 'colorpicker':
					$attributes['type'] = 'colorpicker';
					$attributes['value'] = $field['value'];
					$attributes[] = 'data-wp-color-picker';
					self::render_html_tag( 'input', $attributes );

					break;

				case 'radio':
				case 'radiolist':
					self::render_input_list( $field, array(
						'type' => 'radio'
					), false );

					break;

				case 'checkbox':
					if ( 'on' === $field['value'] ) {
						$attributes['checked'] = 'checked';
					}
					$attributes['value'] = 'on';
					$attributes['type'] = 'checkbox';
					self::render_html_tag( 'input', $attributes );
					break;


				case 'checkboxlist':
					self::render_input_list( $field, array(
						'type' => 'checkbox'
					) );
					break;

				case 'htmllist':
					$type = isset( $field['list-type'] ) ? $field['list-type'] : 'radio';
					self::render_input_list( $field, array(
						'type' => $type,
						'style' => 'display:none'
					), $type !== 'radio' );
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
						'id'                     => $field['input_id'],
						'name'                   => $field['input_name'],
						'value'                  => $field['value'],
						'placeholder'            => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
						'data-suggest',
						'data-suggest-query'     => $query,
						'data-suggest-multiple'  => isset( $field['multiple'] ) ? $field['multiple'] : 'false',
						'data-suggest-separator' => isset( $field['separator'] ) ? $field['separator'] : ','
					) );

					break;

				case 'selectize':
					self::render_selectize_field( $field );

					break;

				case 'select2':
					$action = isset( $field['action'] ) ? $field['action'] : 'foometafield_select2';
					$inner  = '';

					self::render_html_tag( 'select', array(
						'id'    	              => $field['input_id'],
						'name'	                  => $field['input_name'],
						'value'                   => $field['value'],
						'placeholder'             => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
						'data-select2-instance',
						'data-select2-action'     => $action,
						'data-select2-nonce'      => wp_create_nonce( $action ),
						'data-select2-query-type' => isset( $field['query_type'] ) ? $field['query_type'] : 'post',
						'data-select2-query-data' => isset( $field['query_data'] ) ? $field['query_data'] : 'page',
					), $inner );

					break;

				case 'repeater':
					self::render_repeater_field( $field );
					break;

				default:
					//the field type is not natively supported
					if ( isset( $field['function'] ) ) {
						call_user_func( $field['function'], $field );
					}
					break;
			}

			if ( ! empty( $field['desc'] ) ) {
				self::render_html_tag( 'span', array(
						'class' => 'foometafields-field-description'
				), $field['desc'] );
			}

			echo '</div>';
		}

		/**
		 * Render an input list field
		 *
		 * @param $field
		 * @param array $field_attributes
		 * @param bool $use_unique_names
		 */
		static function render_input_list( $field, $field_attributes = array(), $use_unique_names = true ) {
			$i      = 0;
			$spacer = isset( $field['spacer'] ) ? $field['spacer'] : '<div class="foometafields-spacer"></div>';
			foreach ( $field['choices'] as $value => $item ) {
				$label_attributes = array(
					'for' => $field['input_id'] . $i
				);
				$encode = true;
				if ( is_array( $item ) ) {
					$label = $item['label'];
					if ( isset( $item['tooltip'] ) ) {
						$label_attributes['data-balloon'] = $item['tooltip'];
						$label_attributes['data-balloon-length'] = isset( $item['tooltip-length'] ) ? $item['tooltip-length'] : 'small';
						$label_attributes['data-balloon-pos'] = isset( $item['tooltip-position'] ) ? $item['tooltip-position'] : 'down';
					}
					if ( isset( $item['html'] ) ) {
						$label = wp_kses_post( $item['html'] );
						$encode = false;
					}
				} else {
					$label = $item;
				}
				$input_attributes = array(
					'name' => $field['input_name'],
					'id' => $field['input_id'] . $i,
					'value' => $value,
					'tabindex' => $i
				);
				if ( $use_unique_names ) {
					$input_attributes['name'] = $field['input_name'] . '[' . $value . ']';
					if ( isset( $field['value'] ) && isset( $field['value'][$value] ) ) {
						$input_attributes['checked'] = 'checked';
					}
				} else {
					if ( $field['value'] === $value ) {
						$input_attributes['checked'] = 'checked';
					}
				}
				$input_attributes = wp_parse_args( $field_attributes, $input_attributes );

				self::render_html_tag( 'input', $input_attributes );
				self::render_html_tag( 'label', $label_attributes, $label, true, $encode );
				if ( $i < count( $field['choices'] ) - 1 ) {
					echo $spacer;
				}
				$i ++;
			}
		}


		/**
		 * Render the HTML needed for a selectize control
		 *
		 * @param $field
		 */
		static function render_selectize_field( $field ) {
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
					'id'    => $field['input_id'] . '_display',
					'name'  => $field['input_name'] . '[display]',
					'value' => $value['display']
			) );

			$inner = '';

			if ( isset( $value['value'] ) ) {
				$inner = '<option value="' . esc_attr( $value['value'] ) . '" selected="selected">' . esc_html( $value['display'] ) . '</option>';
			}

			self::render_html_tag( 'select', array(
					'id'                     => $field['input_id'],
					'name'                   => $field['input_name'] . '[value]',
					'value'                  => $value['value'],
					'placeholder'            => $field['placeholder'],
					'data-selectize-instance',
					'data-selectize-query'   => $query,
					'data-selectize-display' => $field['input_id'] . '_display'
			), $inner, true, false );
		}
		/**
		 * Render a nested repeater field
		 *
		 * @param $field
		 */
		static function render_repeater_field( $field ) {
			self::render_html_tag( 'div', array(
					'class' => 'foometafields-repeater'
			), null, false );

			self::render_html_tag('table', array(
					'class' => 'wp-list-table widefat fixed striped'
			), null, false );

			//render the table column headers
			echo '<thead><tr>';
			foreach ( $field['fields'] as $child_field ) {
				self::render_html_tag( 'th', array(), $child_field['label'] );
			}
			echo '</tr></thead>';

			//render the repeater rows
			echo '<tbody>';
			if ( is_array( $field['value'] ) ) {
				$row_index = 0;
				foreach( $field['value'] as $row ) {
					$row_index++;
					echo '<tr>';
					foreach ( $field['fields'] as $child_field ) {
						if ( array_key_exists( $child_field['id'], $row ) ) {
							$child_field['value'] = $row[ $child_field['id'] ];
						}
						echo '<td>';
						$child_field['input_id'] = $field['input_id'] . '_' . $child_field['id'] . '_' . $row_index;
						$child_field['input_name'] = $field['input_name'] . '[' . $child_field['id'] . '][]';
						self::render_field( $child_field );
						echo '</td>';
					}
					echo '</tr>';
				}
			}
			echo '</tbody>';

			//render the repeater footer for adding
			echo '<tfoot><tr>';

			foreach ( $field['fields'] as $child_field ) {
				echo '<td>';
				$child_field['input_id'] = $field['input_id'] . '_' . $child_field['id'];
				$child_field['input_name'] = $field['input_name'] . '[' . $child_field['id'] . '][]';
				self::render_field( $child_field, array( 'disabled' => 'disabled' ) );
				echo '</td>';
			}

			echo '</tr></tfoot>';
			echo '</table>';

			self::render_html_tag( 'button', array(
					'class' => 'button foometafields-repeater-add'
			), isset( $field['button'] ) ? $field['button'] : __('Add') );

			echo '</div>';
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
		static function render_html_tag( $tag, $attributes, $inner = null, $close = true, $escape_inner = true ) {
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
			if ( isset( $inner ) ) {
				echo '>';
				if ( $escape_inner ) {
					echo esc_html( $inner );
				} else {
					echo $inner;
				}
				if ( $close ) {
					echo '</' . $tag . '>';
				}
			} else {
				if ( $close ) {
					echo ' />';
				} else {
					echo '>';
				}
			}
		}
	}
}
