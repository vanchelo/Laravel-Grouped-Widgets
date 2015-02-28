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

class EventDispatcher implements EventDispatcherContract
{
    /**
     * @var IlluminateEventDispatcher
     */
    private $dispatcher;

    function __construct(IlluminateEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param  string|object $event
     * @param  mixed         $payload
     *
     * @return array|null
     */
    public function fire($event, $payload = [])
    {
        return $this->dispatcher->fire($event, $payload);
    }
}
