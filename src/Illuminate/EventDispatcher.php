<?php

/**
 * This file is part of Laravel Grouped Widgets package.
 *
 * (c) Brezhnev Ivan <brezhnev.ivan@yahoo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vanchelo\GroupedWidgets\Illuminate;

use Illuminate\Events\Dispatcher as IlluminateEventDispatcher;
use Vanchelo\GroupedWidgets\Contracts\EventDispatcher as EventDispatcherContract;

class EventDispatcher extends IlluminateEventDispatcher implements EventDispatcherContract {}
