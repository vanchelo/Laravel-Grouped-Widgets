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

use Illuminate\Support\ServiceProvider;

class WidgetsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        require __DIR__ . DIRECTORY_SEPARATOR . 'helpers.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Vanchelo\GroupedWidgets\Manager');
        $this->app->bind('grouped-widgets', 'Vanchelo\GroupedWidgets\Manager');

        $this->app->bind(
            'Vanchelo\GroupedWidgets\Contracts\Container',
            'Vanchelo\GroupedWidgets\Illuminate\Container'
        );

        $this->app->bind(
            'Vanchelo\GroupedWidgets\Contracts\EventDispatcher',
            'Vanchelo\GroupedWidgets\Illuminate\EventDispatcher'
        );

        $this->app->bind(
            'Vanchelo\GroupedWidgets\Contracts\Collection',
            'Vanchelo\GroupedWidgets\Illuminate\Collection'
        );

        $this->commands('Vanchelo\GroupedWidgets\Illuminate\Console\Commands\WidgetMakeCommand');
    }

    public function provides()
    {
        return ['grouped-widgets'];
    }
}
