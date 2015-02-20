<?php

if ( ! function_exists('widget'))
{
	function widget()
	{
        if ( ! func_num_args()) return app('grouped-widgets');

        $args = func_get_args();

        return app('grouped-widgets')->make(array_shift($args), $args);
	}
}

if ( ! function_exists('widgets'))
{
	function widgets($group)
	{
		return widget()->group($group);
	}
}
