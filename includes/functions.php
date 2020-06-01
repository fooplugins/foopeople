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
 * @param $path
 * @param $filename
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
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function foopeople_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'foopeople_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Safe way to get value from the request object
 *
 * @param $key
 * @param null $default
 * @param bool $clean
 *
 * @return mixed
 */
function foopeople_safe_get_from_post( $key, $default = null, $clean = true ) {
	if ( isset( $_POST[$key] ) ) {
		$value = wp_unslash( $_POST[$key] );
		if ( $clean ) {
			return foopeople_clean( $value );
		}
		return $value;
	}

	return $default;
}

/**
 * Run foopeople_clean over posted textarea but maintain line breaks.
 *
 * @param  string $var Data to sanitize.
 * @return string
 */
function foopeople_sanitize_textarea( $var ) {
	return implode( "\n", array_map( 'foopeople_clean', explode( "\n", $var ) ) );
}
