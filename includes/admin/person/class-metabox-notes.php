<?php

namespace FooPlugins\FooPeople\Admin\Person;

use FooPlugins\FooPeople\Admin\Metaboxes\CustomPostTypeMetaboxFieldGroup;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxNotes' ) ) {

	class MetaboxNotes extends CustomPostTypeMetaboxFieldGroup {

		function __construct() {
			$field_group = apply_filters( 'FooPlugins\FooPeople\Admin\Person\MetaboxNotes\FieldGroup',
				array(
					'fields' => array(
						array(
							'id'       => 'notes',
							'desc'     => __( 'Capture any notes for this person', 'foopeople' ),
							'type'     => 'repeater',
							'button'   => __( 'Add Note', 'foopeople' ),
							'table-class' => 'fixed',
							'fields'   => array(
								array(
									'id'       => 'index',
									'label'    => __( '#', 'foopeople' ),
									'type'     => 'index',
									'width'    => '2%'
								),
								array(
									'id'       => 'textarea',
									'label'    => __( 'Note', 'foopeople' ),
									'type'     => 'textarea',
									'width'    => '75%'
								),
								array(
									'id'       => '__created_by',
									'label'    => __( 'Created By', 'foopeople' ),
									'type'     => 'readonly',
									'width'    => '8%'
								),
								array(
									'id'       => '__created',
									'label'    => __( 'Date Captured', 'foopeople' ),
									'type'     => 'readonly',
									'display_function' => array( $this, 'format_timestamp' ),
									'width'    => '12%'
								),
								array(
									'id'       => 'manage',
									'type'     => 'manage',
									'width'    => '3%',
									'delete-confirmation-message' => __( 'Are you sure you want to remove this note?', 'foopeople' ),
								),
							)
						)
					)
				)
			);


			parent::__construct(
				array(
					'post_type'      => FOOPEOPLE_CPT_PERSON,
					'metabox_id'     => 'notes',
					'metabox_title'  => __( 'Notes', 'pacepeople' ),
					'meta_key'       => FOOPEOPLE_META_PERSON_NOTES,
					'plugin_url'     => FOOPEOPLE_URL,
					'plugin_version' => FOOPEOPLE_VERSION
				),
				$field_group
			);
		}

		/**
		 * Format a timestamp
		 *
		 * @param $timestamp
		 *
		 * @return string
		 */
		function format_timestamp( $timestamp ) {
			$format = __( 'Y/m/d g:i:s a' );

			if ( function_exists( 'wp_date' ) ) {
				return wp_date( $format, $timestamp );
			}
			return date_i18n( $format, $timestamp );
		}
	}
}
