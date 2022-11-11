<?php

use Oyova\WpRouter\Router;

if ( ! function_exists( 'oyo_router' ) ) {
	function oyo_router(): Router {
		return Router::getInstance();
	}
}

if ( ! function_exists( 'oyo_route' ) ) {
	function oyo_route(
		string $name,
		$parameters = array(),
		bool $absolute = true
	) {
		return oyo_router()->route( $name, $parameters, $absolute );
	}
}
