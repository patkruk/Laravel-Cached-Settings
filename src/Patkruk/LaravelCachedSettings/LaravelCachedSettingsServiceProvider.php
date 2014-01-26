<?php

namespace Patkruk\LaravelCachedSettings;

use Illuminate\Support\ServiceProvider;

class LaravelCachedSettingsServiceProvider extends ServiceProvider {

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
		$this->package('patkruk/laravel-cached-settings');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['cachedSettings.cacheHandler'] = $this->app->share(function($app)
		{
			return new CacheHandler(
					$app->make('cache'),
					$app['config']->getEnvironment(),
					$app['config']->get('laravel-cached-settings::prefix')
			);
		});

		$this->app['cachedSettings.persistentHandler'] = $this->app->share(function($app)
		{
			return new DatabaseHandler(
					$app->make('db'),
					$app['config']->getEnvironment(),
					$app['config']->get('laravel-cached-settings::tableName')
			);
		});

		$this->app['cachedsettings'] = $this->app->share(function($app)
		{
			return new LaravelCachedSettings($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cachedSettings.cacheHandler', 'cachedSettings.persistentHandler', 'cachedsettings');
	}

}