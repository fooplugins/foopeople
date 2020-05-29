<?php
/*
 * PacePeople Admin Menu class
 */

if ( ! class_exists( 'PacePeople_Admin_Menu' ) ) {



	class PacePeople_Admin_Menu {

		function __construct() {
			add_action( 'admin_menu', array( $this, 'register_menu_items' ) );
		}

		/**
		 * @todo add context to the translations
		 */
		function register_menu_items() {
			//we rely on the register_post_type call to add our main menu items
			$parent_slug = foopeople_admin_menu_parent_slug();

			//allow extensions to add their own menu items beforehand
			do_action( 'pacepeople_admin_menu_before' );

			$capability = apply_filters( 'pacepeople_admin_menu_capability', 'manage_options' );

			add_submenu_page(
                $parent_slug,
                sprintf( __( '%s Settings', FOOPEOPLE_SLUG ), foopeople_plugin_name() ),
                __( 'Settings', FOOPEOPLE_SLUG ),
                $capability,
                'pacepeople-settings',
                array( $this, 'pacepeople_settings' )
            );

			add_submenu_page(
                $parent_slug,
                sprintf( __( '%s Help', FOOPEOPLE_SLUG ), foopeople_plugin_name() ),
                __( 'Help', FOOPEOPLE_SLUG ),
                $capability,
                'pacepeople-help',
                array( $this, 'pacepeople_help' )
            );

			if ( current_user_can( 'activate_plugins' ) ) {
				add_submenu_page(
                    $parent_slug,
                    sprintf( __( '%s System Information', FOOPEOPLE_SLUG ), foopeople_plugin_name() ),
                    __( 'System Info', FOOPEOPLE_SLUG ),
                    $capability,
                    'pacepeople-systeminfo',
                    array( $this, 'pacepeople_systeminfo' )
                );
			}

			//allow extensions to add their own menu items afterwards
			do_action( 'pacepeople_admin_menu_after' );
		}

		function pacepeople_settings() {

			$admin_errors = get_transient( 'settings_errors' );
			$show_reset_message = false;

			if ( is_array( $admin_errors ) ) {
				//try to find a reset 'error'
				foreach ( $admin_errors as $error ) {
					if ( 'reset' === $error['setting'] ) {
						$show_reset_message = true;
						break;
					}
				}
			}

			if ( $show_reset_message ) {
				do_action( 'pacepeople_settings_reset' );
				?>
				<div id="message" class="updated">
					<p><strong><?php printf( __( '%s settings reset to defaults.', FOOPEOPLE_SLUG ), foopeople_plugin_name() ); ?></strong></p>
				</div>
			<?php } else if ( isset($_GET['settings-updated']) ) {
				do_action( 'pacepeople_settings_updated' );
				?>
				<div id="message" class="updated">
					<p><strong><?php printf( __( '%s settings updated.', FOOPEOPLE_SLUG ), foopeople_plugin_name() ); ?></strong></p>
				</div>
			<?php }

			$instance = PacePeople_Plugin::get_instance();
			$instance->admin_settings_render_page();
		}

		function pacepeople_help() {
			require_once FOOPEOPLE_PATH . 'includes/admin/view-help.php';
		}

		function pacepeople_systeminfo() {
			require_once FOOPEOPLE_PATH . 'includes/admin/view-system-info.php';
		}
	}
}
