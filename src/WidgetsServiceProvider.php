<?php namespace Vanchelo\GroupedWidgets;

use Illuminate\Support\ServiceProvider;

class WidgetsServiceProvider extends ServiceProvider
{

	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Vanchelo\GroupedWidgets\Manager');
		$this->app->bind('grouped-widgets', 'Vanchelo\GroupedWidgets\Manager');

		$this->commands('Vanchelo\GroupedWidgets\Console\Commands\WidgetMakeCommand');
	}

	public function provides()
	{
		return ['grouped-widgets'];
	}

}
