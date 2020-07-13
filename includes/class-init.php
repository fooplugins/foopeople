<?php
namespace FooPlugins\FooPeople;

/**
 * FooPeople Init Class
 * Runs at the startup of the plugin
 * Assumes after all checks have been made, and all is good to go!
 */

if ( !class_exists( 'FooPlugins\FooPeople\Init' ) ) {

	class Init {

		/**
		 * Initialize the plugin by setting localization, filters, and administration functions.
		 */
		public function __construct() {
			//load the plugin text domain
			add_action( 'plugins_loaded', function() {
				load_plugin_textdomain(
					FOOPEOPLE_SLUG,
					false,
					dirname( plugin_basename( FOOPEOPLE_FILE ) ) . '/languages/'
				);
			});

			if ( is_admin() ) {
				new namespace\Admin\Init();
			}

			//register WP Customizer stuff
			require_once( FOOPEOPLE_PATH . 'includes/customizer/class-customizer.php' );
			new \Customizer();

			//register custom post types
			new namespace\PostTypes\Person();
			// new namespace\PostTypes\Policy();

			//register custom taxonomies
			new namespace\Taxonomies\Team();
			new namespace\Taxonomies\Location();
			new namespace\Taxonomies\Skill();

			// Kick off the 'ol Gutenberg stuffs ekse
			new namespace\Gutenberg\Init();

			//Check if the PRO version of FooPeople is running and run the PRO code
			if ( foopeople_fs()->is__premium_only() ) {
				if ( foopeople_fs()->can_use_premium_code() ) {
					new namespace\Pro\Init();
				}
			}
		}
	}
}
