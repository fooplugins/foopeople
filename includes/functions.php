<?php
/**
 * Contains all the Global common functions used throughout FooPeople
 */

/**
 * If we are on a FooPeople admin screen
 *
 * @param $custom_post_type
 *
 * @return boolean
 */
function foopeople_is_admin_screen( $custom_post_type ) {
	$screen = get_current_screen();
	if ( $screen ) {
		if ( $screen->post_type === $custom_post_type && $screen->id === $custom_post_type ) {
			return true;
		}
	}
	return false;
}

/**
 * Renders a template
 *
 * @param $path
 * @param $filename
 * @param bool $echo
 *
 * @return string|void
 */
function foopeople_render_template( $path = '', $filename, $echo = true, $data = array() ) {
	if( $path ) {
		$path = $path.'/';
	}

	ob_start();
	extract( $data, EXTR_SKIP );
	// load_template(FOOPEOPLE_PATH.'includes/templates/'.$path.$filename.'.php');
	include FOOPEOPLE_PATH.'includes/templates/'.$path.$filename.'.php';
	$output = ob_get_contents();
	ob_end_clean();

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
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

/**
 * Return a sanitized and unslashed key from $_GET
 * @param $key
 *
 * @return string|null
 */
function foopeople_sanitize_key( $key ) {
	if ( isset( $_GET[$key] ) ) {
		return sanitize_key( wp_unslash( $_GET[ $key ] ) );
	}
	return null;
}

/**
 * Return a sanitized and unslashed value from $_GET
 * @param $key
 *
 * @return string|null
 */
function foopeople_sanitize_text( $key ) {
	if ( isset( $_GET[$key] ) ) {
		return sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
	}
	return null;
}




/**
 * Returns all FooPeople
 *
 * @param string|integer $team Taxonomy Team we want to show
 * @param array $excludes People we dont like
 * @return FooePeople[] array of FooPeople
 */
function foopeople_get_people( $team = '', $excludes = false ) {
	$args = array(
		'post_type'     => FOOPEOPLE_CPT_PERSON,
		'post_status'	=> array( 'publish' ),
		'cache_results' => false,
		'nopaging'      => true,
		'posts_per_page' => -1,
		'orderby' => 'title',
	);

	if ( is_array( $excludes ) ) {
		$args['post__not_in'] = $excludes;
	}
	if ( $team ) {
		$field = '';

		if( is_string($team) ) $field = 'slug';
		if( is_int($team) ) $field = 'term_id';

		$args['tax_query'] = array(
			array(
				'taxonomy' => FOOPEOPLE_CT_TEAM,
				'field' => $field,
				'terms' => $team,
			)
		);
	}

	return new WP_Query($args);
}



/**
 * Returns Taxonomy Name
 *
 * @param string|integer $term slug or ID of item
 * @param string $taxonomy taxonomy we want to target
 * @return string Name of Taxonomy
 */
function foopeople_get_taxonomy_name( $term = '', $taxonomy = '' ) {
	if($term && $taxonomy) {
		if( is_string($term) ) $field = 'slug';
		if( is_int($term) ) $field = 'term_id';

		return get_term_by($field, $term, $taxonomy )->name;
	};
}



/**
 * Returns a FooPeople Customizer theme option
 *
 * @param string $option Theme option name
 * @param string $default Default value to return if no option found
 * @return string Theme option value
 */
function foopeople_get_setting( $option, $default = '' ) {
	$setting = get_option( FOOPEOPLE_CUSTOMIZER_PREFIX );

	if( isset($setting[$option] ) ) {
		return $setting[$option];
	} else {
		return $default;
	}
}