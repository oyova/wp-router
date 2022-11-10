<?php

use Oyova\WpRouter\Router;

if ( ! function_exists( 'oyo_route' ) ) {
	function oyo_route(
		string $name,
		$parameters = array(),
		bool $absolute = true
	) {
		return Router::getInstance()->route( $name, $parameters, $absolute );
	}
}
