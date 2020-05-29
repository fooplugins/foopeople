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
	$title        = apply_filters( 'pacepeople_admin_systeminfo_title', sprintf( __( '%s System Information', FOOPEOPLE_SLUG ), foopeople_plugin_name() ) );
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
		__( 'PacePeople version', FOOPEOPLE_SLUG )  			=> $info['version'],
		__( 'WordPress version', FOOPEOPLE_SLUG )   			=> $wp_version,
		__( 'Activated Theme', FOOPEOPLE_SLUG )     			=> $current_theme['Name'],
		__( 'WordPress URL', FOOPEOPLE_SLUG )       			=> get_site_url(),
		__( 'PHP version', FOOPEOPLE_SLUG )         			=> phpversion(),
		__( 'PHP GD', FOOPEOPLE_SLUG )              			=> extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ? __( 'Loaded', FOOPEOPLE_SLUG ) . ' (V' . pacepeople_gdversion() . ')' : __( 'Not found!', FOOPEOPLE_SLUG ),
		__( 'PHP Open SSL', FOOPEOPLE_SLUG )        			=> extension_loaded( 'openssl' ) ? __( 'Loaded', FOOPEOPLE_SLUG ) : __( 'Not found!', FOOPEOPLE_SLUG ),
		__( 'PHP HTTP Wrapper', FOOPEOPLE_SLUG )    			=> in_array( 'http', $stream_wrappers ) ? __( 'Found', FOOPEOPLE_SLUG ) : __( 'Not found!', FOOPEOPLE_SLUG ),
		__( 'PHP HTTPS Wrapper', FOOPEOPLE_SLUG )   			=> in_array( 'https', $stream_wrappers ) ? __( 'Found', FOOPEOPLE_SLUG ) : __( 'Not found!', FOOPEOPLE_SLUG ),
		__( 'HTTPS Mismatch', FOOPEOPLE_SLUG )      			=> $test_image_url_scheme === $home_url_scheme ? __( 'None', FOOPEOPLE_SLUG ) : __( 'There is a protocol mismatch between your site URL and the actual URL!', FOOPEOPLE_SLUG ),
		__( 'PHP Config[allow_url_fopen]', FOOPEOPLE_SLUG ) 	=> ini_get( 'allow_url_fopen' ),
		__( 'PHP Config[allow_url_include]', FOOPEOPLE_SLUG ) => ini_get( 'allow_url_fopen' ),
		__( 'Extensions Endpoint', FOOPEOPLE_SLUG ) 			=> $api->get_extensions_endpoint(),
		__( 'Extensions Errors', FOOPEOPLE_SLUG )   			=> $api->has_extension_loading_errors() == true ? $api->get_extension_loading_errors_response() : __( 'Nope, all good', FOOPEOPLE_SLUG ),
		__( 'Extensions', FOOPEOPLE_SLUG )          			=> $extension_slugs,
		__( 'Extensions Active', FOOPEOPLE_SLUG )   			=> array_keys( $api->get_active_extensions() ),
		__( 'People Templates', FOOPEOPLE_SLUG )   			=> $template_slugs,
		__( 'Lightboxes', FOOPEOPLE_SLUG )          			=> apply_filters( 'pacepeople_people_template_field_lightboxes', array() ),
		__( 'Settings', FOOPEOPLE_SLUG )            			=> $settings,
		__( 'Active Plugins', FOOPEOPLE_SLUG )      			=> $plugins
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