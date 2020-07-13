<?php

namespace FooPlugins\FooPeople\Admin\Person;

use FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetaboxFieldGroup;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxMainDetails' ) ) {

	class MetaboxMainDetails extends CustomPostTypeMetaboxFieldGroup {

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
									'id'       => 'dob',
									'label'    => __( 'Date of Birth', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'required' => true,
									'type'     => 'date',
									'default'  => '',
									'placeholder' => __( '', 'foopeople' )
								),
								array(
									'id'       => 'timezone',
									'label'    => __( 'Timezone', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'type'     => 'select',
									'choices' => array(
										'GMT'		=> __( 'GMT' , 'foopeople' ),
										'UTC-12'	=> __( 'UTC-12' , 'foopeople' ),
										'UTC-11.5'	=> __( 'UTC-11:30' , 'foopeople' ),
										'UTC-11'	=> __( 'UTC-11' , 'foopeople' ),
										'UTC-10.5'	=> __( 'UTC-10:30' , 'foopeople' ),
										'UTC-10'	=> __( 'UTC-10' , 'foopeople' ),
										'UTC-9.5'	=> __( 'UTC-9:30' , 'foopeople' ),
										'UTC-9'		=> __( 'UTC-9' , 'foopeople' ),
										'UTC-8.5'	=> __( 'UTC-8:30' , 'foopeople' ),
										'UTC-8'		=> __( 'UTC-8' , 'foopeople' ),
										'UTC-7.5'	=> __( 'UTC-7:30' , 'foopeople' ),
										'UTC-7'		=> __( 'UTC-7' , 'foopeople' ),
										'UTC-6.5'	=> __( 'UTC-6:30' , 'foopeople' ),
										'UTC-6'		=> __( 'UTC-6' , 'foopeople' ),
										'UTC-5.5'	=> __( 'UTC-5:30' , 'foopeople' ),
										'UTC-5'		=> __( 'UTC-5' , 'foopeople' ),
										'UTC-4.5'	=> __( 'UTC-4:30' , 'foopeople' ),
										'UTC-4'		=> __( 'UTC-4' , 'foopeople' ),
										'UTC-3.5'	=> __( 'UTC-3:30' , 'foopeople' ),
										'UTC-3'		=> __( 'UTC-3' , 'foopeople' ),
										'UTC-2.5'	=> __( 'UTC-2:30' , 'foopeople' ),
										'UTC-2'		=> __( 'UTC-2' , 'foopeople' ),
										'UTC-1.5'	=> __( 'UTC-1:30' , 'foopeople' ),
										'UTC-1'		=> __( 'UTC-1' , 'foopeople' ),
										'UTC-0.5'	=> __( 'UTC-0:30' , 'foopeople' ),
										'UTC+0'		=> __( 'UTC+0' , 'foopeople' ),
										'UTC+0.5'	=> __( 'UTC+0:30' , 'foopeople' ),
										'UTC+1'		=> __( 'UTC+1' , 'foopeople' ),
										'UTC+1.5'	=> __( 'UTC+1:30' , 'foopeople' ),
										'UTC+2'		=> __( 'UTC+2' , 'foopeople' ),
										'UTC+2.5'	=> __( 'UTC+2:30' , 'foopeople' ),
										'UTC+3'		=> __( 'UTC+3' , 'foopeople' ),
										'UTC+3.5'	=> __( 'UTC+3:30' , 'foopeople' ),
										'UTC+4'		=> __( 'UTC+4' , 'foopeople' ),
										'UTC+4.5'	=> __( 'UTC+4:30' , 'foopeople' ),
										'UTC+5'		=> __( 'UTC+5' , 'foopeople' ),
										'UTC+5.5'	=> __( 'UTC+5:30' , 'foopeople' ),
										'UTC+5.75'	=> __( 'UTC+5:45' , 'foopeople' ),
										'UTC+6'		=> __( 'UTC+6' , 'foopeople' ),
										'UTC+6.5'	=> __( 'UTC+6:30' , 'foopeople' ),
										'UTC+7'		=> __( 'UTC+7' , 'foopeople' ),
										'UTC+7.5'	=> __( 'UTC+7:30' , 'foopeople' ),
										'UTC+8'		=> __( 'UTC+8' , 'foopeople' ),
										'UTC+8.5'	=> __( 'UTC+8:30' , 'foopeople' ),
										'UTC+8.75'	=> __( 'UTC+8:45' , 'foopeople' ),
										'UTC+9'		=> __( 'UTC+9' , 'foopeople' ),
										'UTC+9.5'	=> __( 'UTC+9:30' , 'foopeople' ),
										'UTC+10'	=> __( 'UTC+10' , 'foopeople' ),
										'UTC+10.5'	=> __( 'UTC+10:30' , 'foopeople' ),
										'UTC+11'	=> __( 'UTC+11' , 'foopeople' ),
										'UTC+11.5'	=> __( 'UTC+11:30' , 'foopeople' ),
										'UTC+12'	=> __( 'UTC+12' , 'foopeople' ),
										'UTC+12.75'	=> __( 'UTC+12:45' , 'foopeople' ),
										'UTC+13'	=> __( 'UTC+13' , 'foopeople' ),
										'UTC+13.75'	=> __( 'UTC+13:45' , 'foopeople' ),
										'UTC+14'	=> __( 'UTC+14' , 'foopeople' ),
									)
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
								),
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
