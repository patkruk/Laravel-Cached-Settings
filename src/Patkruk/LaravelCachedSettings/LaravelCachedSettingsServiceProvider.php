<?php

namespace Patkruk\LaravelCachedSettings;

use Illuminate\Support\ServiceProvider;
use Patkruk\LaravelCachedSettings\StorageHandlers\CacheHandler;
use Patkruk\LaravelCachedSettings\StorageHandlers\DatabaseHandler;
use Patkruk\LaravelCachedSettings\Commands\CachedSettingsSet;
use Patkruk\LaravelCachedSettings\Commands\CachedSettingsGet;
use Patkruk\LaravelCachedSettings\Commands\CachedSettingsRefreshAll;
use Patkruk\LaravelCachedSettings\Commands\CachedSettingsDeleteAll;
use Patkruk\LaravelCachedSettings\Commands\CachedSettingsImportFile;
use Patkruk\LaravelCachedSettings\Helpers\FileSystemOperations;

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
		// register the cache handler
		$this->app['cachedSettings.cacheHandler'] = $this->app->share(function($app)
		{
			return new CacheHandler(
					$app->make('cache'),
					$app['config']->getEnvironment(),
					$app['config']->get('laravel-cached-settings::prefix')
			);
		});

		// register the persistent storage handler
		$this->app['cachedSettings.persistentHandler'] = $this->app->share(function($app)
		{
			return new DatabaseHandler(
					$app->make('db'),
					$app['config']->getEnvironment(),
					$app['config']->get('laravel-cached-settings::tableName')
			);
		});

		// register the cached settings controller
		$this->app['cachedsettings'] = $this->app->share(function($app)
		{
			return new LaravelCachedSettings(
				$app['config']->getEnvironment(), 					// current environment
				$app['config']['laravel-cached-settings::cache'], 	// package config cache flag
				$app->make('cachedSettings.cacheHandler'),
				$app->make('cachedSettings.persistentHandler'),
				new FileSystemOperations()
			);
		});

		// register the cachedsettings:set command
		$this->app['command.cachedsettings.set'] = $this->app->share(function($app)
		{
			return new CachedSettingsSet($app->make('cachedsettings'));
		});

		// register the cachedsettings:get command
		$this->app['command.cachedsettings.get'] = $this->app->share(function($app)
		{
			return new CachedSettingsGet($app->make('cachedsettings'));
		});

		// register the cachedsettings:refresh-all command
		$this->app['command.cachedsettings.refreshAll'] = $this->app->share(function($app)
		{
			return new CachedSettingsRefreshAll($app->make('cachedsettings'));
		});

		// register the cachedsettings:delete-all command
		$this->app['command.cachedsettings.deleteAll'] = $this->app->share(function($app)
		{
			return new CachedSettingsDeleteAll($app->make('cachedsettings'));
		});

		// register the cachedsettings:import-file command
		$this->app['command.cachedsettings.importFile'] = $this->app->share(function($app)
		{
			return new CachedSettingsImportFile($app->make('cachedsettings'));
		});

		$this->commands(
			'command.cachedsettings.set',
			'command.cachedsettings.get',
			'command.cachedsettings.refreshAll',
			'command.cachedsettings.deleteAll',
			'command.cachedsettings.importFile'
		);
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