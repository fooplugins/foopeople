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



/**
 * Imported from Pace People
 */
// define( 'FOOPEOPLE_CPT_PERSON', 'pacepeople_person' );
// define( 'PACEPEOPLE_CPT_POLICY', 'pacepeople_policy' );

define( 'PACEPEOPLE_CT_DEPARTMENT', 'pacepeople_department' );
define( 'PACEPEOPLE_CT_SKILLS', 'pacepeople_skill' );
define( 'PACEPEOPLE_CT_LOCATION', 'pacepeople_location' );
define( 'PACEPEOPLE_CT_POLICY', 'pacepeople_policy_category' );

define( 'PACEPEOPLE_ADMIN_MENU_PARENT_SLUG', 'edit.php?post_type=pacepeople' );
define( 'PACEPEOPLE_OPTION_VERSION', 'pacepeople-version' );

define( 'PACEPEOPLE_ADMIN_MENU_HELP_SLUG', 'pacepeople-help' );
define( 'PACEPEOPLE_ADMIN_MENU_SETTINGS_SLUG', 'pacepeople-settings' );
define( 'PACEPEOPLE_ADMIN_MENU_SYSTEMINFO_SLUG', 'pacepeople-systeminfo' );

// Default person portrait image
define( 'PACEPEOPLE_PORTRAIT_DEFAULT', 'http://gravatar.com/avatar/?d=mm&s=250' );

// How to define people and groups of people. Ie, Person/people, Employee/Staff
define( 'PACEPEOPLE_SINGULAR', 'Person' );
define( 'PACEPEOPLE_MULTIPLE', 'People' );

// Departments or Teams
define( 'PACEPEOPLE_SINGULAR_GROUP', 'Team' );
define( 'PACEPEOPLE_MULTIPLE_GROUP', 'Teams' );

// Skills or talent
define( 'PACEPEOPLE_SINGULAR_SKILL', 'Skill' );
define( 'PACEPEOPLE_MULTIPLE_SKILL', 'Skills' );

// Location
define( 'PACEPEOPLE_SINGULAR_LOCATION', 'Location' );
define( 'PACEPEOPLE_MULTIPLE_LOCATION', 'Locations' );


// Policies
define( 'PACEPEOPLE_SINGULAR_POLICY', 'Policy' );
define( 'PACEPEOPLE_MULTIPLE_POLICY', 'Policies' );
// Categories for Policies
define( 'PACEPEOPLE_SINGULAR_POLICY_TAXONOMY', 'Category' );
define( 'PACEPEOPLE_MULTIPLE_POLICY_TAXONOMY', 'Categories' );

define( 'PACEPEOPLE_PERSON_META_DETAILS', '_pacepeople_person_details' );
define( 'PACEPEOPLE_PERSON_META_SEARCH', '_pacepeople_person_search' );
