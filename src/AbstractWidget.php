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

use Illuminate\Contracts\Support\Renderable;

abstract class AbstractWidget implements Renderable
{
    abstract public function render();

    function __invoke()
    {
        return call_user_func_array([$this, 'render'], func_get_args());
    }
}
