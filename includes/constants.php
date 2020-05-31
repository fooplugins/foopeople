<?php
/**
 * Contains the Global constants used throughout FooPeople
 */

//options
define( 'FOOPEOPLE_OPTION_DATA', 'foopeople_data' );

//transients
define( 'FOOPEOPLE_TRANSIENT_UPDATED', 'foopeople_updated' );
define( 'FOOPEOPLE_TRANSIENT_ACTIVATION_REDIRECT', 'foopeople_redirect' );

//custom post types
define ( 'FOOPEOPLE_CPT_PERSON', 'foopeople-person' );
define ( 'FOOPEOPLE_CPT_POLICY', 'foopeople-policy' );

//custom taxonomies
define ( 'FOOPEOPLE_CT_TEAM', 'foopeople-team' );
define ( 'FOOPEOPLE_CT_LOCATION', 'foopeople-location' );
define ( 'FOOPEOPLE_CT_SKILL', 'foopeople-skill' );

// post meta
define ( 'FOOPEOPLE_META_PERSON_MAIN', '_foopeople_person_main' );
// Default person portrait image
define( 'FOOPEOPLE_PORTRAIT_DEFAULT', 'http://gravatar.com/avatar/?d=mm&s=250' );

// META details - Person details tabs in single person edit
define( 'FOOPEOPLE_PERSON_META_DETAILS', '_foopeople_person_details' );
define( 'FOOPEOPLE_PERSON_META_SEARCH', '_foopeople_person_search' );