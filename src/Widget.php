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

class Widget
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $abstract;

    /**
     * @var callable
     */
    protected $instance;

    /**
     * @var string
     */
    public $group = 'default';

    /**
     * @var int
     */
    public $order = 0;

    /**
     * @param                 $name
     * @param string|callable $abstract
     */
    function __construct($name, $abstract = null)
    {
        $this->name = $name;
        $this->abstract = $abstract;
    }

    /**
     * Set group name
     *
     * @param string $name
     *
     * @return $this
     */
    public function group($name)
    {
        $this->group = $name;

        return $this;
    }

    /**
     * Set order
     *
     * @param $order
     *
     * @return $this
     */
    public function order($order)
    {
        $this->order = (int) $order;

        return $this;
    }

    /**
     * Determine if the given widget has been resolved
     *
     * @return bool
     */
    public function resolved()
    {
        return $this->instance !== null;
    }

    /**
     * @param callable $instance
     *
     * @return callable
     */
    public function instance(callable $instance = null)
    {
        if (is_null($instance)) {
            return $this->instance;
        }

        return $this->instance = $instance;
    }

    /**
     * @param $name
     * @param $abstract
     *
     * @return Widget
     */
    public static function create($name, $abstract)
    {
        $widget = new self($name);

        if (is_callable($abstract)) {
            $widget->instance($abstract);
        } elseif (is_string($abstract)) {
            $widget->abstract = $abstract;
        } else {
            throw new \InvalidArgumentException('Second argument must be a string or closure');
        }

        return $widget;
    }
}
