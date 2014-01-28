<?php

use Patkruk\LaravelCachedSettings\LaravelCachedSettings;
use Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface;
use Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface;
use Illuminate\Container\Container;

class LaravelCachedSettingsTest extends PHPUnit_Framework_TestCase
{
    protected $app;

    protected $cacheHandler;

    protected $persistentHandler;

    protected $cachedSettings;

    public function setUp()
    {
        $this->app = Mockery::mock('Illuminate\Container\Container');
        $this->app->shouldIgnoreMissing();

        $config = Mockery::mock('config');
        $config->shouldReceive('getEnvironment')->once()->andReturn('testing');

        $this->app['config'] = $config;
        // $this->app['config']['laravel-cached-settings::cache'] = true;

        $this->cacheHandler = Mockery::mock('Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface');
        $this->persistentHandler = Mockery::mock('Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface');

        $this->app->shouldReceive('make')->with('cachedSettings.cacheHandler')
                                         ->andReturn($this->cacheHandler);
        $this->app->shouldReceive('make')->with('cachedSettings.persistentHandler')
                                         ->andReturn($this->persistentHandler);

        $this->cachedSettings = new LaravelCachedSettings($this->app);
    }

    public function testGreen()
    {
        $this->assertTrue(true);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}