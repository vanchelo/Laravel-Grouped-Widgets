<?php namespace Vanchelo\GroupedWidgets\Facades;

use Illuminate\Support\Facades\Facade;

class Widget extends Facade
{

	protected static function getFacadeAccessor()
	{
		return 'grouped-widgets';
	}

}
