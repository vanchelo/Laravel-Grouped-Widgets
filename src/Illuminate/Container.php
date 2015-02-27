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

use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Vanchelo\GroupedWidgets\Contracts\Container as ContainerContract;

class Container implements ContainerContract
{
    /**
     * @var IlluminateContainer
     */
    private $container;

    function __construct(IlluminateContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     * @return mixed
     */
    public function make($abstract)
    {
        return $this->container->make($abstract);
    }
}
