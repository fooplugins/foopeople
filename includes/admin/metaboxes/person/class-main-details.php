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
									'row_data' => array(
										'placeholder' => __( 'The first name of the person.', 'foopeople' ),
									)
								),
								array(
									'id'       => 'surname',
									'title'    => __( 'Last Name', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'row_data' => array(
										'placeholder' => __( 'The last name/surname/family name of the person.', 'foopeople' ),
									)
								),
								array(
									'id'       => 'preferred',
									'title'    => __( 'Preferred Name', 'foopeople' ),
									'desc'     => __( 'You can override the full name for the person. Leave blank to default the full name to be "First Name + Last Name".', 'foopeople' ),
									'required' => false,
									'type'     => 'text',
									'default'  => '',
									'row_data' => array(
										'placeholder' => __( 'You can override what the full name for the person will be', 'foopeople' ),
									)
								),
								array(
									'id'       => 'jobtitle',
									'title'    => __( 'Job Title', 'foopeople' ),
									'desc'     => __( '', 'foopeople' ),
									'required' => true,
									'type'     => 'text',
									'default'  => '',
									'row_data' => array(
										'placeholder' => __( 'Job title', 'foopeople' ),
									)
								)

							)
						),
						array(
							'id'     => 'portrait',
							'label'  => __( 'Portrait', 'foopeople' ),
							'icon'   => 'dashicons-format-image',
							'fields' => array(),
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
							'fields' => array(),
							'taxonomy' => FOOPEOPLE_CT_TEAM
						),
						array(
							'id'     => 'skills',
							'label'  => __( 'Skills', 'foopeople' ),
							'icon'   => 'dashicons-tag',
							'fields' => array(),
							'taxonomy' => FOOPEOPLE_CT_SKILL
						),
						array(
							'id'     => 'locations',
							'label'  => __( 'Locations', 'foopeople' ),
							'icon'   => 'dashicons-admin-site',
							'fields' => array(),
							'taxonomy' => FOOPEOPLE_CT_LOCATION
						)
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
