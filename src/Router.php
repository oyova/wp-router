<?php

namespace Oyova\WpRouter;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router as LaravelRouter;
use Illuminate\Routing\UrlGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Router extends Container {
	public function __construct() {
		static::setInstance( $this );
		$this->register_route_facade();
		$this->capture_http_request();
		$this->register_router();
		$this->register_url_generator();
		$this->handle_http_request();
	}

	protected function register_route_facade(): void {
		Route::clearResolvedInstances();
		Route::setFacadeApplication( $this );
	}

	protected function capture_http_request(): void {
		$this->singleton( 'request', fn () => Request::capture() );
	}

	protected function register_router(): void {
		$this->singleton(
			'router',
			fn () => new LaravelRouter( new Dispatcher( $this ), $this )
		);
	}

	protected function register_url_generator(): void {
		$this->singleton(
			'url',
			fn () => new UrlGenerator(
				$this['router']->getRoutes(),
				$this['request']
			)
		);
	}

	protected function handle_http_request(): void {
		add_filter(
			'parse_request',
			function () {
				try {
					$response = $this['router']->dispatch( $this['request'] );
					$response->send();
					exit;
				} catch ( NotFoundHttpException $e ) {
					// Simply return, WP will handle the rest!
					return;
				}
			}
		);
	}

	public function request(): Request {
		return $this['request'];
	}

	public function route(
		string $name,
		array|string|int|float|bool $parameters = array(),
		bool $absolute = true
	): string {
		return $this['url']->route( $name, $parameters, $absolute );
	}
}
