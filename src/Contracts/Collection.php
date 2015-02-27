<?php

/**
 * This file is part of Laravel Grouped Widgets package.
 *
 * (c) Brezhnev Ivan <brezhnev.ivan@yahoo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vanchelo\GroupedWidgets\Contracts;

interface Collection
{
    /**
     * Put an item in the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $value
     *
     * @return void
     */
    public function put($key, $value);

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Run a filter over each of the items.
     *
     * @param  callable $callback
     *
     * @return static
     */
    public function filter(callable $callback);

    /**
     * Sort the collection using the given callback.
     *
     * @param  callable|string $callback
     * @param  int             $options
     * @param  bool            $descending
     *
     * @return $this
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false);

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty();
}
