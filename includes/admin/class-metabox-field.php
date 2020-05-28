<?php

if ( ! class_exists( 'PacePeople_MetaBox_Field' ) ) {

	class PacePeople_MetaBox_Field {

		/**
		 * Renders a metabox field
		 *
		 * @param array $field
		 */
		static function render( $field = array(), $meta_key ) {
			//only declare up front so no debug warnings are shown
			$type = $id = $desc = $default = $placeholder = $choices = $class = $spacer = null;

			extract( $field );

			//get the value
            if ( !isset( $field['value'] ) ) {
                $field['value'] = apply_filters('pacepeople_metabox_field_value', false, $field);
            }

			//we only need choices for specific field types
			$field['choices'] = apply_filters( 'pacepeople_metabox_field_choices', $choices, $field );

			$field_class = empty($class) ? '' : ' class="' . $class . '"';

			//allow for UI customization
			do_action( 'pacepeople_metabox_field_before', $field );

			echo '<div class="pacepeople_metabox_field-' . $type . '">';

			switch ( $type ) {

				case 'html':
					echo $desc;
					$desc = '';
					break;

				case 'checkbox':
//					if ( isset($people->settings[$id]) && $people->settings[$id] == 'on' ) {
//						$field['value'] = 'on';
//					} else if ( ! isset($people->settings) && $default == 'on' ) {
//						$field['value'] = 'on';
//					} else {
//						$field['value'] = '';
//					}

					$checked = 'on' === $field['value'] ? ' checked="checked"' : '';
					echo '<input' . $field_class . ' type="checkbox" id="PacePeopleSettings_' . $meta_key . '_' . $id . '" name="' . $meta_key . '[' . $id . ']" value="on"' . $checked . ' />';
					break;

				case 'select':
					echo '<select' . $field_class . ' id="PacePeopleSettings_' . $meta_key . '_' . $id . '" name="' . $meta_key . '[' . $id . ']">';
					foreach ( $choices as $value => $label ) {
						$selected = '';
						if ( $field['value'] == $value ) {
							$selected = ' selected="selected"';
						}
						echo '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
					}

					echo '</select>';
					break;

				case 'radio':
					$i = 0;
					$spacer = isset($spacer) ? $spacer : '<br />';
					foreach ( $choices as $value => $label ) {
						$selected = '';
						if ( $field['value'] == $value ) {
							$selected = ' checked="checked"';
						}
						echo '<input' . $field_class . $selected . ' type="radio" name="' . $meta_key . '[' . $id . ']"  id="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '" value="' . $value . '"> <label for="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '">' . $label . '</label>';
						if ( $i < count( $choices ) - 1 ) {
							echo $spacer;
						}
						$i++;
					}
					break;

				case 'textarea':
					echo '<textarea' . $field_class . ' id="PacePeopleSettings_' . $meta_key . '_' . $id . '" name="' . $meta_key . '[' . $id . ']" placeholder="' . $placeholder . '">' . esc_attr( $field['value'] ) . '</textarea>';

					break;

				case 'text':
					echo '<input' . $field_class . ' type="text" id="PacePeopleSettings_' . $meta_key . '_' . $id . '" name="' . $meta_key . '[' . $id . ']" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'colorpicker':

					$opacity_attribute = empty($opacity) ? '' : ' data-show-alpha="true"';

					echo '<input ' . $opacity_attribute . ' class="colorpicker" type="text" id="PacePeopleSettings_' . $meta_key . '_' . $id . '" name="' . $meta_key . '[' . $id . ']" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'number':
					$min = isset($min) ? $min : 0;
					$step = isset($step) ? $step : 1;
					echo '<input class="regular-text ' . $class . '" type="number" step="' . $step . '" min="' . $min .'" id="PacePeopleSettings_' . $meta_key . '_' . $id . '" name="' . $meta_key . '[' . $id . ']" placeholder="' . $placeholder . '" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'checkboxlist':
					$i = 0;
					foreach ( $choices as $value => $label ) {

						$checked = '';
						if ( isset($field['value'][$value]) && $field['value'][$value] == $value ) {
							$checked = 'checked="checked"';
						}

						echo '<input' . $field_class . ' ' . $checked . ' type="checkbox" name="' . $meta_key . '[' . $id . '][' . $value . ']" id="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '" value="' . $value . '" data-value="' . $value . '"> <label for="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '">' . $label . '</label>';
						if ( $i < count( $choices ) - 1 ) {
							echo '<br />';
						}
						$i++;
					}

					break;
				case 'icon':
					$i = 0;
					$input_name = $meta_key . '[' . $id . ']';
					$icon_html = '';
					foreach ( $choices as $value => $icon ) {
						$selected = ( $field['value'] == $value ) ? ' checked="checked"' : '';
						$icon_html .= '<input style="display:none" name="' . $input_name. '" id="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '" ' . $selected . ' type="radio" value="' . $value . '" tabindex="' . $i . '"/>';
						$title = $icon['label'];
						$img = $icon['img'];
						$icon_html .= '<label for="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '" data-balloon-length="small" data-balloon-pos="down" data-balloon="' . $title . '"><img src="' . $img . '" /></label>';
						$i++;
					}
					echo $icon_html;
					break;

				case 'htmlicon':
					$i = 0;
					$input_name = $meta_key . '[' . $id . ']';
					$icon_html = '';
					foreach ( $choices as $value => $icon ) {
						$selected = ( $field['value'] == $value ) ? ' checked="checked"' : '';
						$icon_html .= '<input style="display:none" name="' . $input_name. '" id="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '" ' . $selected . ' type="radio" value="' . $value . '" tabindex="' . $i . '"/>';
						$title = $icon['label'];
						$html = $icon['html'];
						$icon_html .= '<label for="PacePeopleSettings_' . $meta_key . '_' . $id . $i . '" data-balloon-length="small" data-balloon-pos="down" data-balloon="' . $title . '">' . $html . '</label>';
						$i++;
					}
					echo $icon_html;
					break;

				default:
					do_action( 'pacepeople_metabox_field_' . $type, $field );
					break;
			}

			if (!empty($suffix)) {
				echo $suffix;
			}

			echo '</div>';

			//allow for more customization
			do_action( 'pacepeople_metabox_field_after', $field );
		}
	}
}
