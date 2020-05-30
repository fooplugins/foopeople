<?php
/**
 * Contains all the Global common functions used throughout FooPeople
 */

/**
 * If we are on a FooPeople admin screen
 *
 * @return boolean
 */
function is_foopeople_admin_screen() {
	$screen = get_current_screen();
	if($screen) {
		if($screen->post_type == FOOPEOPLE_CPT_PERSON && $screen->id == FOOPEOPLE_CPT_PERSON) {
			return true;
		}
	}
	return false;
}

/**
 * If we are on a FooPeople admin screen
 *
 * @return string
 */
function render_template($path, $filename) {
	ob_start();
	load_template(FOOPEOPLE_PATH.'includes/templates/'.$path.'/'.$filename.'.php');
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}




/**
 * Custom Autoloader used throughout FooPeople
 *
 * @param $class
 */
function foopeople_autoloader( $class ) {
	/* Only autoload classes from this namespace */
	if ( false === strpos( $class, FOOPEOPLE_NAMESPACE ) ) {
		return;
	}

	/* Remove namespace from class name */
	$class_file = str_replace( FOOPEOPLE_NAMESPACE . '\\', '', $class );

	/* Convert sub-namespaces into directories */
	$class_path = explode( '\\', $class_file );
	$class_file = array_pop( $class_path );
	$class_path = strtolower( implode( '/', $class_path ) );

	/* Convert class name format to file name format */
	$class_file = foopeople_uncamelize( $class_file );
	$class_file = str_replace( '_', '-', $class_file );
	$class_file = str_replace( '--', '-', $class_file );

	/* Load the class */
	require_once FOOPEOPLE_DIR . '/includes/' . $class_path . '/class-' . $class_file . '.php';
}

/**
 * Convert a CamelCase string to camel_case
 *
 * @param $str
 *
 * @return string
 */
function foopeople_uncamelize( $str ) {
	$str    = lcfirst( $str );
	$lc     = strtolower( $str );
	$result = '';
	$length = strlen( $str );
	for ( $i = 0; $i < $length; $i ++ ) {
		$result .= ( $str[ $i ] == $lc[ $i ] ? '' : '_' ) . $lc[ $i ];
	}

	return $result;
}

/**
 * Safe way to get value from an array
 *
 * @param $key
 * @param $array
 * @param $default
 *
 * @return mixed
 */
function foopeople_safe_get_from_array( $key, $array, $default ) {
	if ( is_array( $array ) && array_key_exists( $key, $array ) ) {
		return $array[ $key ];
	} else if ( is_object( $array ) && property_exists( $array, $key ) ) {
		return $array->{$key};
	}

	return $default;
}

/**
 * Safe way to get value from the request object
 *
 * @param $key
 *
 * @return mixed
 */
function foopeople_safe_get_from_request( $key ) {
	return foopeople_safe_get_from_array( $key, $_REQUEST, null );
}



/**
 * PacePeople global functions
 */

/**
 * Returns the name of the plugin. (Allows the name to be overridden)
 *
 * @return string
 *
 * @since 0.0.1
 */
function foopeople_plugin_name() {
	return apply_filters( FOOPEOPLE_FILTER_PLUGIN_NAME, 'foopeople' );
}

/**
 * Return the PacePeople saved setting, or a default value
 *
 * @param string $key The key for the setting
 *
 * @param bool $default The default if no value is saved or found
 *
 * @return mixed
 *
 * @since 0.0.1
 */
function pacepeople_get_setting( $key, $default = false ) {
	$pacepeople = PacePeople_Plugin::get_instance();

	return $pacepeople->options()->get( $key, pacepeople_get_default( $key, $default ) );
}

/**
 * Helper method for getting default settings
 *
 * @param string $key The default config key to retrieve.
 *
 * @param bool $default The default if no default is set or found
 *
 * @return string Key value on success, false on failure.
 */
function pacepeople_get_default( $key, $default = false ) {

	$defaults = array();

	// A handy filter to override the defaults
	$defaults = apply_filters( FOOPEOPLE_FILTER_DEFAULTS, $defaults );

	// Return the key specified.
	return isset($defaults[ $key ]) ? $defaults[ $key ] : $default;
}

/**
 * Returns the PacePeople Add People Url within the admin
 *
 * @return string The Url to the PacePeople Add People page in admin
 */
function pacepeople_admin_add_people_url() {
	return admin_url( 'post-new.php?post_type=' . PACEPEOPLE_CPT_PERSON );
}

/**
 * Returns the PacePeople help page Url within the admin
 *
 * @return string The Url to the PacePeople help page in admin
 */
function pacepeople_admin_help_url() {
	return admin_url( add_query_arg( array( 'page' => PACEPEOPLE_ADMIN_MENU_HELP_SLUG ), foopeople_admin_menu_parent_slug() ) );
}

/**
 * Returns the PacePeople settings page Url within the admin
 *
 * @return string The Url to the PacePeople settings page in admin
 */
function pacepeople_admin_settings_url() {
	return admin_url( add_query_arg( array( 'page' => PACEPEOPLE_ADMIN_MENU_SETTINGS_SLUG ), foopeople_admin_menu_parent_slug() ) );
}


/**
 * Returns the PacePeople system info page Url within the admin
 *
 * @return string The Url to the PacePeople system info page in admin
 */
function pacepeople_admin_systeminfo_url() {
	return admin_url( add_query_arg( array( 'page' => PACEPEOPLE_ADMIN_MENU_SYSTEMINFO_SLUG ), foopeople_admin_menu_parent_slug() ) );
}

/**
 * Get the admin menu parent slug
 * @return string
 */
function foopeople_admin_menu_parent_slug() {
	return apply_filters( FOOPEOPLE_FILTER_ADMIN_MENU_PARENT_SLUG, PACEPEOPLE_ADMIN_MENU_PARENT_SLUG );
}

/**
 * Helper function to build up the admin menu Url
 * @param array $extra_args
 *
 * @return string|void
 */
function pacepeople_build_admin_menu_url( $extra_args = array() ) {
	$url = admin_url( foopeople_admin_menu_parent_slug() );
	if ( ! empty( $extra_args ) ) {
		$url = add_query_arg( $extra_args, $url );
	}
	return $url;
}

/**
 * Helper function for adding a pacepeople sub menu
 *
 * @param $menu_title
 * @param string $capability
 * @param string $menu_slug
 * @param $function
 */
function pacepeople_add_submenu_page( $menu_title, $capability, $menu_slug, $function ) {
	add_submenu_page(
		foopeople_admin_menu_parent_slug(),
		$menu_title,
		$menu_title,
		$capability,
		$menu_slug,
		$function
	);
}

/**
 * Returns all PacePeople galleries
 *
 * @return PacePeople[] array of PacePeople galleries
 */
function pacepeople_get_all_people( $excludes = false ) {
	$args = array(
		'post_type'     => PACEPEOPLE_CPT_PERSON,
		'post_status'	=> array( 'publish', 'draft' ),
		'cache_results' => false,
		'nopaging'      => true,
	);

	if ( is_array( $excludes ) ) {
		$args['post__not_in'] = $excludes;
	}

	return get_posts( $args );
}

/**
 * Allow PacePeople to enqueue stylesheet and allow them to be enqueued in the head on the next page load
 *
 * @param $handle string
 * @param $src string
 * @param array $deps
 * @param bool $ver
 * @param string $media
 */
function pacepeople_enqueue_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
	do_action( 'pacepeople_enqueue_style', $handle, $src, $deps, $ver, $media );
}

/**
 * Performs a check to see if the plugin has been updated, and perform any housekeeping if necessary
 */
function pacepeople_perform_version_check() {
	$checker = new PacePeople_Version_Check();
	$checker->perform_check();
}

/**
 * Does a full uninstall of the plugin including all data and settings!
 */
function pacepeople_uninstall() {

	if ( !current_user_can( 'install_plugins' ) ) exit;

	//delete all people posts first
	global $wpdb;
	$query = "SELECT p.ID FROM {$wpdb->posts} AS p WHERE p.post_type IN (%s)";
	$people_post_ids = $wpdb->get_col( $wpdb->prepare( $query, PACEPEOPLE_CPT_PERSON ) );

	if ( !empty( $people_post_ids ) ) {
		$deleted = 0;
		foreach ( $people_post_ids as $post_id ) {
			$del = wp_delete_post( $post_id );
			if ( false !== $del ) {
				++$deleted;
			}
		}
	}

	//delete all options
	if ( is_network_admin() ) {
		delete_site_option( PACEPEOPLE_SLUG );
	} else {
		delete_option( PACEPEOPLE_SLUG );
	}
	delete_option( PACEPEOPLE_OPTION_VERSION );

	//let any extensions clean up after themselves
	do_action( 'pacepeople_uninstall' );
}

if ( !function_exists('wp_get_raw_referer') ) {
	/**
	 * Retrieves unvalidated referer from '_wp_http_referer' or HTTP referer.
	 *
	 * Do not use for redirects, use {@see wp_get_referer()} instead.
	 *
	 * @since 1.4.9
	 * @return string|false Referer URL on success, false on failure.
	 */
	function wp_get_raw_referer() {
		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
			return wp_unslash( $_REQUEST['_wp_http_referer'] );
		} else if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			return wp_unslash( $_SERVER['HTTP_REFERER'] );
		}

		return false;
	}
}

/**
 * Renders a template and returns the result
 * @param       $name
 * @param array $data
 *
 * @return string
 */
function pacepeople_render_template($name, $data = array(), $render = true) {
	if (!$render) ob_start();
	extract( $data, EXTR_SKIP );
	include PACEPEOPLE_PATH . 'includes/templates/'.$name.'.php';
	if (!$render) return ob_get_clean();
}