<?php namespace Stillat\Menu\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Menu extends Facade {

	public static function getFacadeAccessor()
	{
		return 'stillat-menu';
	}

}