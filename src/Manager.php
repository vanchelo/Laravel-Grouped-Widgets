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

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Collection;

class Manager
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @var Events Events dispatcher
     */
    private $events;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param Container  $app
     * @param Events     $events
     * @param Collection $collection
     */
    function __construct(
            Container $app,
            Events $events,
            Collection $collection
    ) {
        $this->app = $app;
        $this->events = $events;
        $this->collection = $collection;
    }

    /**
     * Register a widget
     *
     * @param string          $name
     * @param string|callable $abstract
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
     * @param $name
     * @return Widget
     */
    protected function resolve($name)
    {
        if ( ! $this->resolved($name)) {
            /** @var Widget $widget */
            $widget = $this->get($name);

            $widget->instance($this->app->make($widget->abstract));

            if ( ! $this->isInvokable($widget->instance())) {
                $this->throwNotInvokableException($widget);
            }

            $this->events->fire('widget.resolved: ' . $name, compact('widget'));
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
     * @param $widget
     * @return bool
     */
    protected function isInvokable($widget)
    {
        return is_callable($widget);
    }

    /**
     * @param string $name
     * @param array  $data
     * @return mixed
     */
    public function make($name, $data = [])
    {
        $widget = $this->resolve($name);

        $this->events->fire('widget.rendering: ' . $name, compact('widget', 'data'));

        return $this->execute($widget, $data);
    }

    /**
     * @param Widget $widget
     * @param array  $data
     * @return mixed
     */
    protected function execute($widget, $data = [])
    {
        return call_user_func_array($widget->instance(), $data);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function resolved($name)
    {
        return $this->has($name) && $this->get($name)->resolved();
    }

    /**
     * Determine if a widget exists in the collection by name
     *
     * @param string $name Widget name
     * @return bool
     */
    public function has($name)
    {
        return $this->collection->has($name);
    }

    /**
     * Get widget from collection by name
     *
     * @param string $name Widget name
     * @return Widget
     */
    public function get($name)
    {
        return $this->collection->get($name);
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param $name
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
     * @param string $name
     * @param string $delimiter
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

        $this->events->fire('widget.group.rendering: ' . $name, compact('group', 'output'));

        return implode($delimiter, $output);
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return mixed|null
     */
    function __call($name, $arguments)
    {
        return $this->has($name) ? $this->make($name, $arguments) : null;
    }
}
