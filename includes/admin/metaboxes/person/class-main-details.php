<?php

namespace FooPlugins\FooPeople\Admin\Metaboxes\Person;

use FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetabox;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Metaboxes\Person\MainDetails' ) ) {

	class MainDetails extends CustomPostTypeMetabox {

		function __construct() {
			$field_group = apply_filters( 'FooPlugins\FooPeople\Admin\Metaboxes\Person\TabFieldGroup',
				array(
					'tabs' => array(
						array(
							'id'     => 'personal',
							'label'  => __( 'Personal', 'foopeople' ),
							'icon'   => 'dashicons-admin-users',
							'fields' => array(
								array(
									'id'       => 'firstname',
									'title'    => __( 'First Name', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'The first name of the person.', 'foopeople' ),
								),
								array(
									'id'       => 'surname',
									'title'    => __( 'Last Name', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'The last name/surname/family name of the person.', 'foopeople' ),
								),
								array(
									'id'       => 'preferred',
									'title'    => __( 'Preferred Name', 'foopeople' ),
									'desc'     => __( 'You can override the full name for the person. Leave blank to default the full name to be "First Name + Last Name".', 'foopeople' ),
									'required' => false,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'This could be a nickname or a shortened name', 'foopeople' ),
								),
								array(
									'id'       => 'jobtitle',
									'title'    => __( 'Job Title', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'placeholder' => __( 'Job title', 'foopeople' ),
								),
								array(
									'id'       => 'manager',
									'title'    => __( 'Line Manager', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'required' => true,
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
									'title'    => __( 'Email', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
								),
								array(
									'id'       => 'phonenumber',
									'title'    => __( 'Phone Number', 'foopeople' ),
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
							'id'     => 'test',
							'label'  => __( 'Test', 'foopeople' ),
							'icon'   => 'dashicons-universal-access-alt',
							'fields' => array(
								array(
									'id'       => 'help',
									'title'    => __( 'Help Field', 'foopeople' ),
									'desc'     => __( 'This tab shows all the available fields. This is a help field.', 'foopeople' ),
									'type'     => 'help',
								),
								array(
									'id'       => 'section',
									'title'    => __( 'Section Field', 'foopeople' ),
									'desc'     => __( 'This desc will not be shown for a section.', 'foopeople' ),
									'type'     => 'section',
								),
								array(
									'id'       => 'singlecolumn',
									'title'    => __( 'Single Column Field', 'foopeople' ),
									'desc'     => __( 'A single column field can show a title and description.', 'foopeople' ),
									'type'     => 'singlecolumn',
								),
								array(
									'id'       => 'singlecolumn',
									'desc'     => __( 'Another single column field with no title set, and a class of foometafields-single-column-icon foometafields-single-column-icon-promo', 'foopeople' ),
									'class'    => 'foometafields-single-column-icon foometafields-single-column-icon-promo',
									'type'     => 'singlecolumn',
								),
								array(
									'id'       => 'text',
									'title'    => __( 'Text Field', 'foopeople' ),
									'desc'     => __( 'A test text field', 'foopeople' ),
									'type'     => 'text',
								),
								array(
									'id'       => 'number',
									'title'    => __( 'Number Field', 'foopeople' ),
									'desc'     => __( 'A test number field', 'foopeople' ),
									'type'     => 'number',
								),
								array(
									'id'       => 'textarea',
									'title'    => __( 'Textarea Field', 'foopeople' ),
									'desc'     => __( 'A test textarea field', 'foopeople' ),
									'type'     => 'textarea',
								),
								array(
									'id'       => 'checkbox',
									'title'    => __( 'Checkbox Field', 'foopeople' ),
									'desc'     => __( 'A test Checkbox field', 'foopeople' ),
									'type'     => 'checkbox',
								),
								array(
									'id'       => 'select',
									'title'    => __( 'Select Field', 'foopeople' ),
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
									'id'       => 'radio',
									'title'    => __( 'Radio Field', 'foopeople' ),
									'desc'     => __( 'A test radio field', 'foopeople' ),
									'type'     => 'radio',
									'choices' => array(
										'option1' => __( 'Option 1', 'foopeople' ),
										'option2' => __( 'Option 2', 'foopeople' ),
										'option3' => __( 'Option 3', 'foopeople' ),
										'option4' => __( 'Option 4', 'foopeople' ),
									)
								),
								array(
									'id'       => 'checkboxlist',
									'title'    => __( 'Checkboxlist Field', 'foopeople' ),
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
									'title'    => __( 'Color Field', 'foopeople' ),
									'desc'     => __( 'A test HTML5 color input field', 'foopeople' ),
									'type'     => 'color',
								),
								array(
									'id'       => 'colorpicker',
									'title'    => __( 'Colorpicker Field', 'foopeople' ),
									'desc'     => __( 'A test colorpicker field using the colorpicker built into WP', 'foopeople' ),
									'type'     => 'colorpicker',
								),
								array(
									'id'       => 'htmllist',
									'title'    => __( 'HTML List Field', 'foopeople' ),
									'desc'     => __( 'A test html list field', 'foopeople' ),
									'type'     => 'htmllist',
									'choices' => array(
										'option1' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=1" />',
											'label' => __( 'Option 1', 'foopeople' ),
										),
										'option2' => array(
											'html' => '<img src="https://dummyimage.com/32x32/000/fff&text=2" />',
											'label' => __( 'Option 2', 'foopeople' ),
										)
									)
								),
								array(
									'id'       => 'suggest',
									'title'    => __( 'Suggest Field (autocomplete without a key)', 'foopeople' ),
									'type'     => 'suggest',
									'default'  => '',
									'placeholder' => __( 'Start typing', 'foopeople' ),
									'query_type' => 'post',
									'query_data' => FOOPEOPLE_CPT_PERSON
								),
								array(
									'id'       => 'selectize',
									'title'    => __( 'selectize Field (autocomplete with a key)', 'foopeople' ),
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
					'meta_key'       => FOOPEOPLE_META_PERSON_MAIN,
					'plugin_url'     => FOOPEOPLE_URL,
					'plugin_version' => FOOPEOPLE_VERSION
				),
				$field_group
			);
		}
	}
}
