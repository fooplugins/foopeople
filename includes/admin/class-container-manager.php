<?php
namespace FooPlugins\FooPeople\Admin;

use FooPlugins\FooPeople\Admin\FooFields\Manager;

/**
 * FooPeople FooFields Manager Class
 */

if ( !class_exists( __NAMESPACE__ . '\ContainerManager' ) ) {

	class ContainerManager extends Manager {

		public function __construct() {
			parent::__construct( array(
				'id'             => FOOPEOPLE_SLUG,
				'text_domain'    => FOOPEOPLE_SLUG,
				'plugin_url'     => FOOPEOPLE_URL,
				'plugin_version' => FOOPEOPLE_VERSION
			) );
		}
	}
}
