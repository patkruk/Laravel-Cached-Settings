<?php

use Patkruk\LaravelCachedSettings\StorageHandlers\CacheHandler;
use Illuminate\Cache\CacheManager;

class CacheHandlerTest extends PHPUnit_Framework_TestCase
{
    protected $laravelCache;

    protected $cacheHandler;

    public function setUp()
    {
        $this->laravelCache = Mockery::mock('Illuminate\Cache\CacheManager');

        $this->cacheHandler = new CacheHandler($this->laravelCache, 'local', 'settings');
    }

    public function testGet()
    {
        $this->laravelCache->shouldReceive('get')
                           ->with('settings-localkey')
                           ->once()
                           ->andReturn('value');

        $result = $this->cacheHandler->get('key');

        $this->assertEquals('value', $result);
    }

    public function testGetUsesLocalCache()
    {
        $this->laravelCache->shouldReceive('put')
                           ->withArgs(array('settings-localkey', 'value', 0))
                           ->once()
                           ->andReturn(true);

        // set a new key
        $this->cacheHandler->set('key', 'value');

        $result = $this->cacheHandler->get('key');

        $this->assertEquals('value', $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}