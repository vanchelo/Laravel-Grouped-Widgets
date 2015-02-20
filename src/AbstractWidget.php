<?php namespace Vanchelo\GroupedWidgets;

use Illuminate\Contracts\Support\Renderable;

abstract class AbstractWidget implements Renderable
{

	abstract public function render();

	function __invoke()
	{
		return call_user_func_array([$this, 'render'], func_get_args());
	}

}
