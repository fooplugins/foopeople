<?php

namespace FooPlugins\FooPeople\Admin\Person;

use FooPlugins\FooPeople\Admin\FooFields\Metabox;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxNotes' ) ) {

	class MetaboxNotes extends Metabox {

		function __construct() {
			$fields = apply_filters( 'FooPlugins\FooPeople\Admin\Person\MetaboxNotes\Fields',
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
									'type'     => 'repeater-index',
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
									'render' => array( $this, 'format_timestamp' ),
									'width'    => '12%'
								),
								array(
									'id'       => 'manage',
									'type'     => 'repeater-delete',
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
					'metabox_title'  => __( 'Notes', 'foopeople' ),
					'text_domain'    => FOOPEOPLE_SLUG,
					'meta_key'       => FOOPEOPLE_META_PERSON_NOTES,
					'plugin_url'     => FOOPEOPLE_URL,
					'plugin_version' => FOOPEOPLE_VERSION,
					'fields'         => $fields
				)
			);
		}

		/**
		 * Format a timestamp
		 *
		 * @param $field
		 *
		 * @return string
		 */
		function format_timestamp( $field ) {
			$format = __( 'Y/m/d g:i:s a' );

			$timestamp = $field->value();

			if ( function_exists( 'wp_date' ) ) {
				return wp_date( $format, $timestamp );
			}
			return date_i18n( $format, $timestamp );
		}
	}
}
