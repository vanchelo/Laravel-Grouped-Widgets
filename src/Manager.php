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

use Vanchelo\GroupedWidgets\Contracts\Container;
use Vanchelo\GroupedWidgets\Contracts\EventDispatcher;
use Vanchelo\GroupedWidgets\Contracts\Collection;

class Manager
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @var EventDispatcher Events dispatcher
     */
    private $event;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param Container       $app
     * @param EventDispatcher $events
     * @param Collection      $collection
     */
    function __construct(Container $app, EventDispatcher $events, Collection $collection)
    {
        $this->app = $app;
        $this->event = $events;
        $this->collection = $collection;
    }

    /**
     * Register a widget
     *
     * @param string          $name
     * @param string|callable $abstract
     *
     * @return $this
     */
    public function register($name, $abstract)
    {
        $widget = Widget::create($name, $abstract);

        $this->collection->put($name, $widget);

        return $this;
    }

    /**
     * Register a widget if it hasn't already been registered
     *
     * @param string          $name
     * @param string|callable $abstract
     *
     * @return $this
     */
    public function registerIf($name, $abstract)
    {
        if ( ! $this->has($name)) {
            $this->register($name, $abstract);
        }

        return $this;
    }

    /**
     * Resolve a widget by name from the collection
     *
     * @param $name
     *
     * @return Widget
     */
    protected function resolve($name)
    {
        if ( ! $this->resolved($name)) {
            /** @var Widget $widget */
            $widget = $this->get($name);

            $widget->instance($this->app->make($widget->abstract));

            if ( ! $this->isInvokable($widget)) {
                $this->throwNotInvokableException($widget);
            }

            $this->event->fire('widget.resolved: ' . $name, compact('widget'));
        }

        return $this->get($name);
    }

    /**
     * @param Widget $widget
     */
    protected function throwNotInvokableException($widget)
    {
        throw new \RuntimeException(
            sprintf('Widget "%s" is not invokable', $widget->abstract)
        );
    }

    /**
     * @param Widget $widget
     *
     * @return bool
     */
    protected function isInvokable(Widget $widget)
    {
        return is_callable($widget->instance());
    }

    /**
     * Resolve and execute widget by name
     *
     * @param string $name
     * @param array  $data
     *
     * @return mixed
     */
    public function make($name, $data = [])
    {
        $widget = $this->resolve($name);

        $this->event->fire('widget.rendering: ' . $name, compact('widget', 'data'));

        return $this->execute($widget, $data);
    }

    /**
     * Execute widget
     *
     * @param Widget $widget
     * @param array  $data
     *
     * @return mixed
     */
    protected function execute($widget, $data = [])
    {
        return call_user_func_array($widget->instance(), $data);
    }

    /**
     * Determine if the given widget has been resolved
     *
     * @param string $name
     *
     * @return bool
     */
    protected function resolved($name)
    {
        return $this->has($name) && $this->get($name)->resolved();
    }

    /**
     * Determine if a widget exists in the widgets collection by name
     *
     * @param string $name Widget name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->collection->has($name);
    }

    /**
     * Get widget from the widgets collection by name
     *
     * @param string $name Widget name
     *
     * @return Widget
     */
    public function get($name)
    {
        return $this->collection->get($name);
    }

    /**
     * Get the widgets collection
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Get group by name from the widgets collection
     *
     * @param $name
     *
     * @return Collection
     */
    public function getGroup($name)
    {
        $group = $this->collection->filter(function ($widget) use ($name) {
            return $widget->group == $name;
        });

        return $group->sortBy('order');
    }

    /**
     * Get and render group by name
     *
     * @param string $name
     * @param string $delimiter
     *
     * @return string
     */
    public function group($name, $delimiter = '')
    {
        $output = [];

        $group = $this->getGroup($name);

        if ($group->isEmpty()) {
            return null;
        }

        foreach ($group as $key => $value) {
            $output[] = $this->make($key);
        }

        $this->event->fire('widget.group.rendering: ' . $name, compact('group', 'output'));

        return implode($delimiter, $output);
    }

    /**
     * Handle dynamic method calls
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed|null
     */
    function __call($name, $arguments)
    {
        return $this->has($name) ? $this->make($name, $arguments) : null;
    }
}
