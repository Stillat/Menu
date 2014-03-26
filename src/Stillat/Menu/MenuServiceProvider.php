<?php namespace Stillat\Menu;

use Stillat\Menu\MenuManager;
use Stillat\Menu\Renderers\DefaultRenderer;
use Stillat\Menu\Renderers\RendererInterface;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('stillat/menu', 'stillat');
	}

	/**
	 * Register the menu service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('stillat-menu.renderer', function()
		{
			return new DefaultRenderer;
		});

		$this->app->bindShared('stillat-menu', function()
		{
			return new MenuManager($this->app->make('stillat-menu.renderer'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('stillat-menu', 'stillat-menu.renderer');
	}

}
