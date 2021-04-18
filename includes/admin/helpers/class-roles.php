<?php
namespace FooPlugins\FooPeople\Admin\Helpers;

use WP_Roles;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Helpers\Roles' ) ) {

	class Roles {

		/**
		 * Create roles and capabilities for FooPeople
		 */
		public function create_roles() {
			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
			}

			// Dummy gettext calls to get strings in the catalog.
			/* translators: user role */
			_x( 'Employee', 'User role', 'foopeople' );
			/* translators: user role */
			_x( 'Line Manager', 'User role', 'foopeople' );
			/* translators: user role */
			_x( 'HR Manager', 'User role', 'foopeople' );

			// Employee role.
			add_role(
				'employee',
				'Employee',
				array(
					'read' => true,
				)
			);

			// Line Manager role.
			add_role(
				'line_manager',
				'Line Manager',
				array(
					'read' => true,
				)
			);

			// HR Manager role.
			$hr_manager_role = add_role(
				'hr_manager',
				'HR Manager',
				get_role( 'administrator' )->capabilities
			);

			$hr_manager_role->remove_cap( 'activate_plugins' );
			$hr_manager_role->remove_cap( 'edit_plugins' );
			$hr_manager_role->remove_cap( 'edit_themes' );

			$capabilities = $this->get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'hr_manager', $cap );
					$wp_roles->add_cap( 'administrator', $cap );
				}
			}
		}

		/**
		 * Get capabilities for FooPeople - these are assigned to admin/HR manager during installation or reset.
		 *
		 * @return array
		 */
		private function get_core_capabilities() {
			$capabilities = array();

			$capabilities['core'] = array(
				'manage_foopeople',
				'view_foopeople_reports',
			);

			$capability_types = array( 'person' => 'people', 'policy' => 'policies' );

			foreach ( $capability_types as $singular => $plural ) {

				$capabilities[ $singular ] = array(
					// Post type.
					"edit_{$singular}",
					"read_{$singular}",
					"delete_{$singular}",
					"edit_{$plural}",
					"edit_others_{$plural}",
					"publish_{$plural}",
					"read_private_{$plural}",
					"delete_{$plural}",
					"delete_private_{$plural}",
					"delete_published_{$plural}",
					"delete_others_{$plural}",
					"edit_private_{$plural}",
					"edit_published_{$plural}",

					// Terms.
					"manage_{$singular}_terms",
					"edit_{$singular}_terms",
					"delete_{$singular}_terms",
					"assign_{$singular}_terms",
				);
			}

			return $capabilities;
		}

		/**
		 * Remove FooPeople roles.
		 */
		public function remove_roles() {
			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
			}

			$capabilities = $this->get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'shop_manager', $cap );
					$wp_roles->remove_cap( 'administrator', $cap );
				}
			}

			remove_role( 'employee' );
			remove_role( 'line_manager' );
			remove_role( 'hr_manager' );
		}
	}
}
