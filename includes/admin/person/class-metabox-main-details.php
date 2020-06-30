<?php

namespace FooPlugins\FooPeople\Admin\Person;

use FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetabox;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxMainDetails' ) ) {

	class MetaboxMainDetails extends CustomPostTypeMetabox {

		function __construct() {
			$field_group = apply_filters( 'FooPlugins\FooPeople\Admin\Person\MetaboxMainDetails\FieldGroup',
				array(
					'tabs' => array(
						array(
							'id'     => 'personal',
							'label'  => __( 'Personal', 'foopeople' ),
							'icon'   => 'dashicons-admin-users',
							'fields' => array(
								array(
									'id'       => 'firstname',
									'label'    => __( 'First Name', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'The first name of the person.', 'foopeople' ),
									'search_index' => true,
								),
								array(
									'id'       => 'surname',
									'label'    => __( 'Last Name', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'The last name/surname/family name of the person.', 'foopeople' ),
								),
								array(
									'id'       => 'preferred',
									'label'    => __( 'Preferred Name', 'foopeople' ),
									'desc'     => __( 'You can override the full name for the person. Leave blank to default the full name to be "First Name + Last Name".', 'foopeople' ),
									'required' => false,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'This could be a nickname or a shortened name', 'foopeople' ),
								),
								array(
									'id'       => 'jobtitle',
									'label'    => __( 'Job Title', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'Job title', 'foopeople' ),
								),
								array(
									'id'       => 'manager',
									'label'    => __( 'Line Manager', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'type'     => 'selectize',
									'default'  => '',
									'placeholder' => __( 'Start typing the manager name', 'foopeople' ),
									'query_type' => 'post',
									'query_data' => FOOPEOPLE_CPT_PERSON
								)
							)
						),
						array(
							'id'     => 'portrait',
							'label'  => __( 'Portrait', 'foopeople' ),
							'icon'   => 'dashicons-format-image',
							'fields' => array(),
							'type'   => 'featured_image',
							'featuredImage' => true
						),
						array(
							'id'     => 'contact',
							'label'  => __( 'Contact', 'foopeople' ),
							'icon'   => 'dashicons-phone',
							'fields' => array(
								array(
									'id'       => 'email',
									'label'    => __( 'Email', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
								),
								array(
									'id'       => 'phonenumber',
									'label'    => __( 'Phone Number', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
								),
							)
						),
						array(
							'id'     => 'teams',
							'label'  => __( 'Teams', 'foopeople' ),
							'icon'   => 'dashicons-groups',
							'type'   => 'taxonomy',
							'fields' => array(),
							'taxonomy' => FOOPEOPLE_CT_TEAM
						),
						array(
							'id'     => 'skills',
							'label'  => __( 'Skills', 'foopeople' ),
							'icon'   => 'dashicons-tag',
							'type'   => 'taxonomy',
							'fields' => array(),
							'taxonomy' => FOOPEOPLE_CT_SKILL
						),
						array(
							'id'     => 'locations',
							'label'  => __( 'Locations', 'foopeople' ),
							'icon'   => 'dashicons-admin-site',
							'type'   => 'taxonomy',
							'fields' => array(),
							'taxonomy' => FOOPEOPLE_CT_LOCATION
						),
						array(
							'id'     => 'nested',
							'label'  => __( 'Parent', 'foopeople' ),
							'icon'   => 'dashicons-universal-access',
							'tabs' => array(
								array(
									'id'     => 'child1',
									'label'  => __( 'Child 1', 'foopeople' ),
									'fields' => array(
										array(
											'id'       => 'repeater',
											//'label'    => __( 'Repeater Field', 'foopeople' ),
											'desc'     => __( 'A repeater field', 'foopeople' ),
											'type'     => 'repeater',
											'button'   => __( 'Add Note', 'foopeople' ),
											'fields'   => array(
												array(
													'id'       => 'text',
													'label'    => __( 'Text Field', 'foopeople' ),
													'desc'     => __( 'A test text field', 'foopeople' ),
													'type'     => 'text',
												),
												array(
													'id'       => 'number',
													'label'    => __( 'Number Field', 'foopeople' ),
													'desc'     => __( 'A test number field', 'foopeople' ),
													'type'     => 'number',
												),
												array(
													'id'       => 'textarea',
													'label'    => __( 'Textarea Field', 'foopeople' ),
													'desc'     => __( 'A test textarea field', 'foopeople' ),
													'type'     => 'textarea',
												),
												array(
													'id'       => 'checkbox',
													'label'    => __( 'Checkbox Field', 'foopeople' ),
													'desc'     => __( 'A test Checkbox field', 'foopeople' ),
													'type'     => 'checkbox',
												),
												array(
													'id'       => 'select',
													'label'    => __( 'Select Field', 'foopeople' ),
													'desc'     => __( 'A test select field', 'foopeople' ),
													'type'     => 'select',
													'choices' => array(
														'option1' => __( 'Option 1', 'foopeople' ),
														'option2' => __( 'Option 2', 'foopeople' ),
														'option3' => __( 'Option 3', 'foopeople' ),
														'option4' => __( 'Option 4', 'foopeople' ),
													)
												),
											)
										),
										array(
											'id'       => 'help',
											'label'    => __( 'Help Field', 'foopeople' ),
											'desc'     => __( 'This tab shows all the available fields. This is a help field.', 'foopeople' ),
											'type'     => 'help',
										),
										array(
											'id'       => 'section',
											'label'    => __( 'Section Field', 'foopeople' ),
											'desc'     => __( 'This desc will not be shown for a section.', 'foopeople' ),
											'type'     => 'section',
										)
									)
								),
								array(
									'id'     => 'child2',
									'label'  => __( 'Child 2', 'foopeople' ),
									'fields' => array(
										array(
											'id'       => 'help2',
											'label'    => __( 'Help Field 2', 'foopeople' ),
											'desc'     => __( 'This tab shows all the available fields. This is a help field.', 'foopeople' ),
											'type'     => 'help',
										),
										array(
											'id'       => 'section2',
											'label'    => __( 'Section Field 2', 'foopeople' ),
											'desc'     => __( 'This desc will not be shown for a section.', 'foopeople' ),
											'type'     => 'section',
										)
									)
								),
							)
						),
						array(
							'id'     => 'test',
							'label'  => __( 'Test', 'foopeople' ),
							'icon'   => 'dashicons-universal-access-alt',
							'fields' => array(
								array(
									'id'       => 'help',
									'desc'     => __( 'This tab shows all the available fields. This is a help field.', 'foopeople' ),
									'type'     => 'help',
								),
								array(
									'id'       => 'heading',
									'desc'     => __( 'Heading Field', 'foopeople' ),
									'type'     => 'heading',
								),
								array(
									'id'       => 'singlecolumn',
									'desc'     => __( 'Another help field but with class set to foometafields-icon foometafields-icon-promo', 'foopeople' ),
									'class'    => 'foometafields-icon-promo',
									'type'     => 'help',
								),
								array(
									'id'       => 'text',
									'label'    => __( 'Block Field', 'foopeople' ),
									'desc'     => __( 'This field should have the label above the input', 'foopeople' ),
									'type'     => 'text',
								),
								array(
									'id'       => 'text1',
									'label'    => __( 'Inline Field', 'foopeople' ),
									'desc'     => __( 'This field should have the label next to the input', 'foopeople' ),
									'layout'   => 'inline',
									'type'     => 'text',
								),
								array(
									'id'       => 'text2',
									'desc'     => __( 'This field will not have a label', 'foopeople' ),
									'type'     => 'text',
								),
								array(
									'id'       => 'number',
									'label'    => __( 'Number Field', 'foopeople' ),
									'desc'     => __( 'A test number field', 'foopeople' ),
									'type'     => 'number',
								),
								array(
									'id'       => 'textarea',
									'label'    => __( 'Textarea Field', 'foopeople' ),
									'desc'     => __( 'A test textarea field', 'foopeople' ),
									'type'     => 'textarea',
								),

								array(
									'id'       => 'select',
									'label'    => __( 'Select Field', 'foopeople' ),
									'desc'     => __( 'A test select field', 'foopeople' ),
									'type'     => 'select',
									'choices' => array(
										'option1' => __( 'Option 1', 'foopeople' ),
										'option2' => __( 'Option 2', 'foopeople' ),
										'option3' => __( 'Option 3', 'foopeople' ),
										'option4' => __( 'Option 4', 'foopeople' ),
									)
								),
								array(
									'id'       => 'checkbox',
									'label'    => __( 'Checkbox Field', 'foopeople' ),
									'layout'   => 'inline',
									'type'     => 'checkbox',
								),
								array(
									'id'       => 'radioinline',
									'label'    => __( 'Radio Fields Inline', 'foopeople' ),
									'desc'     => __( 'Radio Fields Inline', 'foopeople' ),
									'layout'   => 'inline',
									'type'     => 'radiolist',
									'spacer'   => '',
									'choices' => array(
										'option1' => __( 'Option 1', 'foopeople' ),
										'option2' => __( 'Option 2', 'foopeople' ),
										'option3' => __( 'Option 3', 'foopeople' ),
										'option4' => __( 'Option 4', 'foopeople' ),
									)
								),
								array(
									'id'       => 'checkboxlistinline',
									'label'    => __( 'Checkboxes Inline', 'foopeople' ),
									'desc'     => __( 'A test checkboxlist field', 'foopeople' ),
									'layout'   => 'inline',
									'type'     => 'checkboxlist',
									'choices' => array(
										'option1' => __( 'Option 1', 'foopeople' ),
										'option2' => __( 'Option 2', 'foopeople' ),
										'option3' => __( 'Option 3', 'foopeople' ),
										'option4' => __( 'Option 4', 'foopeople' ),
									)
								),


								array(
									'id'       => 'radiostacked',
									'label'    => __( 'Radio Fields Stacked', 'foopeople' ),
									'desc'     => __( 'Radio Fields Stacked', 'foopeople' ),
									'type'     => 'radiolist',
									// 'spacer'   => '',
									'choices' => array(
										'option1' => __( 'Option 1', 'foopeople' ),
										'option2' => __( 'Option 2', 'foopeople' ),
										'option3' => __( 'Option 3', 'foopeople' ),
										'option4' => __( 'Option 4', 'foopeople' ),
									)
								),
								array(
									'id'       => 'checkboxlist',
									'label'    => __( 'Checkboxlist stacked', 'foopeople' ),
									'desc'     => __( 'A test checkboxlist field', 'foopeople' ),
									'type'     => 'checkboxlist',
									'choices' => array(
										'option1' => __( 'Option 1', 'foopeople' ),
										'option2' => __( 'Option 2', 'foopeople' ),
										'option3' => __( 'Option 3', 'foopeople' ),
										'option4' => __( 'Option 4', 'foopeople' ),
									)
								),

								array(
									'id'       => 'color',
									'label'    => __( 'Color Field', 'foopeople' ),
									'desc'     => __( 'A test HTML5 color input field', 'foopeople' ),
									'type'     => 'color',
								),
								array(
									'id'       => 'colorpicker',
									'label'    => __( 'Colorpicker Field', 'foopeople' ),
									'desc'     => __( 'A test colorpicker field using the colorpicker built into WP', 'foopeople' ),
									'type'     => 'colorpicker',
								),
								array(
									'id'       => 'htmllist',
									'label'    => __( 'HTML List Field (radio)', 'foopeople' ),
									'desc'     => __( 'A test html list field', 'foopeople' ),
									'type'     => 'htmllist',
									'spacer'   => '',
									'choices' => array(
										'option1' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=1" />',
											'label' => __( 'Option 1', 'foopeople' ),
											'tooltip' => __( 'A tooltip for Option 1', 'foopeople' ),
										),
										'option2' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=2" />',
											'label' => __( 'Option 2', 'foopeople' ),
										)
									)
								),
								array(
									'id'       => 'htmllist2',
									'label'    => __( 'HTML List Field (checkbox)', 'foopeople' ),
									'list-type'=> 'checkbox',
									'desc'     => __( 'A test html list field', 'foopeople' ),
									'type'     => 'htmllist',
									'spacer'   => '',
									'choices' => array(
										'option1' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=1" />',
											'label' => __( 'Option 1', 'foopeople' ),
											'tooltip' => __( 'A tooltip for Option 1', 'foopeople' ),
										),
										'option2' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=2" />',
											'label' => __( 'Option 2', 'foopeople' ),
										),
										'option3' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=3" />',
											'label' => __( 'Option 3', 'foopeople' ),
										)
									)
								),
								array(
									'id'       => 'suggest',
									'label'    => __( 'Suggest Field (autocomplete without a key)', 'foopeople' ),
									'type'     => 'suggest',
									'default'  => '',
									'placeholder' => __( 'Start typing', 'foopeople' ),
									'query_type' => 'post',
									'query_data' => FOOPEOPLE_CPT_PERSON
								),
								array(
									'id'       => 'selectize',
									'label'    => __( 'selectize Field (autocomplete with a key)', 'foopeople' ),
									'type'     => 'Selectize',
									'placeholder' => __( 'Start typing', 'foopeople' ),
									'query_type' => 'post',
									'query_data' => FOOPEOPLE_CPT_PERSON
								)
							)
						),
					)
				) );

			parent::__construct(
				array(
					'post_type'      => FOOPEOPLE_CPT_PERSON,
					'metabox_id'     => 'details',
					'metabox_title'  => __( 'Main Details', 'pacepeople' ),
					'priority'       => 'high',
					'meta_key'       => FOOPEOPLE_META_PERSON_MAIN,
					'plugin_url'     => FOOPEOPLE_URL,
					'plugin_version' => FOOPEOPLE_VERSION
				),
				$field_group
			);
		}
	}
}
