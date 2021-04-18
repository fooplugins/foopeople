<?php
global $wp_version;
/**
 * Get which version of GD is installed, if any.
 *
 * Returns the version (1 or 2) of the GD extension.
 */
function pacepeople_gdversion() {
	if ( ! extension_loaded( 'gd' ) ) {
		return '0';
	}

	// Use the gd_info() function if possible.
	if ( function_exists( 'gd_info' ) ) {
		$ver_info = gd_info();
		preg_match( '/\d/', $ver_info['GD Version'], $match );

		return $match[0];
	}
	// If phpinfo() is disabled use a specified / fail-safe choice...
	if ( preg_match( '/phpinfo/', ini_get( 'disable_functions' ) ) ) {
		return '?';
	}
	// ...otherwise use phpinfo().
	ob_start();
	phpinfo( 8 );
	$info = ob_get_contents();
	ob_end_clean();
	$info = stristr( $info, 'gd version' );
	preg_match( '/\d/', $info, $match );

	return $match[0];
}

if ( current_user_can( 'activate_plugins' ) ) {
	$instance     = PacePeople_Plugin::get_instance();
	$info         = $instance->get_plugin_info();
	$title        = apply_filters( 'pacepeople_admin_systeminfo_title', sprintf( __( '%s System Information', 'foopeople' ), foopeople_plugin_name() ) );
	$support_text = apply_filters( 'pacepeople_admin_systeminfo_supporttext', sprintf( __( 'Below is some information about your server configuration. You can use this info to help debug issues you may have with %s.' ), foopeople_plugin_name() ) );
	$api          = new PacePeople_Extensions_API();
	//clear any extenasion cache
	$api->clear_cached_extensions();
	$extension_slugs = $api->get_all_slugs();

	//get all people templates
	$template_slugs = array();
	foreach ( pacepeople_people_templates() as $template ) {
		$template_slugs[] = $template['slug'];
	}

	//get all activated plugins
	$plugins = array();
	foreach ( get_option('active_plugins') as $plugin_slug => $plugin ) {
		$plugins[] = $plugin;
	}

	$current_theme = wp_get_theme();

	$pacepeople = PacePeople_Plugin::get_instance();
	$settings = $pacepeople->options()->get_all();

	$stream_wrappers = stream_get_wrappers();

	$test_image_url_scheme = parse_url( pacepeople_test_thumb_url() ,PHP_URL_SCHEME );
	$home_url_scheme = parse_url( home_url() ,PHP_URL_SCHEME );
	$image_file_contents = file_get_contents( pacepeople_test_thumb_url() );

	$debug_info = array(
		__( 'PacePeople version', 'foopeople' )  			=> $info['version'],
		__( 'WordPress version', 'foopeople' )   			=> $wp_version,
		__( 'Activated Theme', 'foopeople' )     			=> $current_theme['Name'],
		__( 'WordPress URL', 'foopeople' )       			=> get_site_url(),
		__( 'PHP version', 'foopeople' )         			=> phpversion(),
		__( 'PHP GD', 'foopeople' )              			=> extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ? __( 'Loaded', 'foopeople' ) . ' (V' . pacepeople_gdversion() . ')' : __( 'Not found!', 'foopeople' ),
		__( 'PHP Open SSL', 'foopeople' )        			=> extension_loaded( 'openssl' ) ? __( 'Loaded', 'foopeople' ) : __( 'Not found!', 'foopeople' ),
		__( 'PHP HTTP Wrapper', 'foopeople' )    			=> in_array( 'http', $stream_wrappers ) ? __( 'Found', 'foopeople' ) : __( 'Not found!', 'foopeople' ),
		__( 'PHP HTTPS Wrapper', 'foopeople' )   			=> in_array( 'https', $stream_wrappers ) ? __( 'Found', 'foopeople' ) : __( 'Not found!', 'foopeople' ),
		__( 'HTTPS Mismatch', 'foopeople' )      			=> $test_image_url_scheme === $home_url_scheme ? __( 'None', 'foopeople' ) : __( 'There is a protocol mismatch between your site URL and the actual URL!', 'foopeople' ),
		__( 'PHP Config[allow_url_fopen]', 'foopeople' ) 	=> ini_get( 'allow_url_fopen' ),
		__( 'PHP Config[allow_url_include]', 'foopeople' ) => ini_get( 'allow_url_fopen' ),
		__( 'Extensions Endpoint', 'foopeople' ) 			=> $api->get_extensions_endpoint(),
		__( 'Extensions Errors', 'foopeople' )   			=> $api->has_extension_loading_errors() == true ? $api->get_extension_loading_errors_response() : __( 'Nope, all good', 'foopeople' ),
		__( 'Extensions', 'foopeople' )          			=> $extension_slugs,
		__( 'Extensions Active', 'foopeople' )   			=> array_keys( $api->get_active_extensions() ),
		__( 'People Templates', 'foopeople' )   			=> $template_slugs,
		__( 'Lightboxes', 'foopeople' )          			=> apply_filters( 'pacepeople_people_template_field_lightboxes', array() ),
		__( 'Settings', 'foopeople' )            			=> $settings,
		__( 'Active Plugins', 'foopeople' )      			=> $plugins
	);

	$debug_info = apply_filters( 'pacepeople_admin_debug_info', $debug_info );
	?>
	<style>
		.pacepeople-debug {
			width: 100%;
			font-family: "courier new";
			height: 500px;
		}
	</style>
	<div class="wrap about-wrap">
		<h1><?php echo $title; ?></h1>

		<div class="about-text">
			<?php echo $support_text; ?>
		</div>
    <textarea class="pacepeople-debug">
<?php foreach ( $debug_info as $key => $value ) {
	echo $key . ' : ';
	print_r( $value );
	echo "\n";
} ?>
    </textarea>
	</div>
<?php }