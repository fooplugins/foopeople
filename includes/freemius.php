<?php
/**
 * Runs all the Freemius initialization code for FooPeople
 */

if ( ! function_exists( 'foopeople_fs' ) ) {
	// Create a helper function for easy SDK access.
	function foopeople_fs() {
		global $foopeople_fs;

		if ( ! isset( $foopeople_fs ) ) {
			// Include Freemius SDK.
			require_once FOOPEOPLE_PATH . '/freemius/start.php';

			//You should replace the below code with your Freemius integration code
			// from https://dashboard.freemius.com/#!/live/plugins/6209/integration/
			// We assume your Freemius function name is foopeople_fs.
			// If not, you will need to update it and all references to it.

			$foopeople_fs = fs_dynamic_init( array(
				'id'                  => '6209',
				'slug'                => 'foopeople',
				'type'                => 'plugin',
				'is_premium'          => true,
				'has_addons'          => false,
				'has_paid_plans'      => true
			) );
		}

		return $foopeople_fs;
	}

	// Init Freemius.
	foopeople_fs();
	// Signal that SDK was initiated.
	do_action( 'foopeople_fs_loaded' );
}
