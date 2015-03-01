<?php

/**
 * This file is part of Laravel Grouped Widgets package.
 *
 * (c) Brezhnev Ivan <brezhnev.ivan@yahoo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vanchelo\GroupedWidgets;

use Vanchelo\GroupedWidgets\Contracts\Widget;

abstract class AbstractWidget implements Widget
{
    function __invoke()
    {
        if ( ! method_exists($this, 'render')) {
            return null;
        }

        return call_user_func_array([$this, 'render'], func_get_args());
    }
}
