<?php
/**
 * Runs all the Freemius initialization code for FooPeople
 */

if ( ! function_exists( 'foopeople_fs' ) ) {
	// Create a helper function for easy SDK access.
	function foopeople_fs() {
		global $foopeople_fs;

		if ( ! isset( $foopeople_fs ) ) {
			// Activate multisite network integration.
			if ( ! defined( 'WP_FS__PRODUCT_6209_MULTISITE' ) ) {
				define( 'WP_FS__PRODUCT_6209_MULTISITE', true );
			}

			// Include Freemius SDK.
			require_once FOOPEOPLE_PATH . '/freemius/start.php';

			$foopeople_fs = fs_dynamic_init( array(
				'id'                  => '6209',
				'slug'                => 'foopeople',
				'type'                => 'plugin',
				'public_key'          => 'pk_4f04cda091ea0fe0626e7894fc203',
				'is_premium'          => true,
				'premium_suffix'      => 'Pro',
				// If your plugin is a serviceware, set this option to false.
				'has_premium_version' => true,
				'has_addons'          => false,
				'has_paid_plans'      => true,
				'trial'               => array(
					'days'               => 7,
					'is_require_payment' => true,
				),
				'has_affiliation'     => 'selected',
				'menu'                => array(
					'slug'           => 'foopeople',
				)
			) );
		}

		return $foopeople_fs;
	}

	// Init Freemius.
	foopeople_fs();
	// Signal that SDK was initiated.
	do_action( 'foopeople_fs_loaded' );
}
