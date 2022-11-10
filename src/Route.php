<?php

namespace Oyova\WpRouter;

use Illuminate\Support\Facades\Facade;

class Route extends Facade {

	protected static function getFacadeAccessor(): string {
		return 'router';
	}
}
