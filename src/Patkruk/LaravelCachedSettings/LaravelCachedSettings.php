<?php

namespace Patkruk\LaravelCachedSettings;

use \Illuminate\Foundation\Application;

class LaravelCachedSettings
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    protected $env;

    protected $cacheHandler;

    protected $persistentHandler;

    /**
     * Class constructor method.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        // get environment
        $this->env = $app['config']->getEnvironment();

        if ($app['config']['laravel-cached-settings::cache'] == true) {
            $this->cacheHandler = $app->make('cachedSettings.cacheHandler');
        }

        $this->persistentHandler = $app->make('cachedSettings.persistentHandler');
    }

    public function set($key, $value)
    {
        $this->cacheHandler->set($key, $value);
    }

    public function get($key)
    {
        return $this->cacheHandler->get($key);
    }

    public function delete($key)
    {
        $this->cacheHandler->delete($key);
    }

    public function has($key)
    {
        return $this->cacheHandler->has($key);
    }

    public function refresh($key)
    {

    }

    public function refreshAll()
    {
        // get alle the keys from the database
        // and then erase them from the cache
    }

    public function getKeys()
    {

    }

    public function getAll()
    {

    }
}
