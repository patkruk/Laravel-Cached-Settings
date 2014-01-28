<?php

use Patkruk\LaravelCachedSettings\LaravelCachedSettings;
use Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface;
use Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface;

class LaravelCachedSettingsTest extends PHPUnit_Framework_TestCase
{
    protected $app;

    protected $cacheHandler;

    protected $persistentHandler;

    protected $cachedSettings;

    protected $storedSetting;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        $this->cacheHandler = Mockery::mock('Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface');
        $this->persistentHandler = Mockery::mock('Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface');

        $this->storedSetting = Mockery::mock('storedSetting');

        $this->cachedSettings = new LaravelCachedSettings(
                        'local',
                        true,
                        $this->cacheHandler,
                        $this->persistentHandler
        );
    }

    public function testSetWithNewKeyAndCacheEnabled()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->cacheHandler->shouldReceive('set')
                           ->withArgs(array($key, $value))
                           ->once()
                           ->andReturn(true);

        $this->persistentHandler->shouldReceive('add')
                                ->withArgs(array($key, $value))
                                ->once()
                                ->andReturn(true);

        $this->persistentHandler->shouldReceive('has')
                                ->with($key)
                                ->once()
                                ->andReturn(false);

        $this->assertTrue($this->cachedSettings->set($key, $value));
    }

    public function testSetWithExistingKeyAndCacheDisabled()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->cachedSettings = new LaravelCachedSettings(
                        'local',
                        false,
                        $this->cacheHandler,
                        $this->persistentHandler
        );

        $this->persistentHandler->shouldReceive('update')
                                ->withArgs(array($key, $value))
                                ->once()
                                ->andReturn(true);

        $this->persistentHandler->shouldReceive('has')
                                ->with($key)
                                ->once()
                                ->andReturn(true);

        $this->assertTrue($this->cachedSettings->set($key, $value));
    }

    public function testGetReturnsKeyFromCache()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->cacheHandler->shouldReceive('get')
                           ->with($key)
                           ->once()
                           ->andReturn($value);

        $this->cacheHandler->shouldReceive('has')
                           ->with($key)
                           ->once()
                           ->andReturn(true);

        $result = $this->cachedSettings->get($key);

        $this->assertEquals($value, $result);
    }

    public function testGetReturnsKeyFromPersistentStorageIfItsNotInCache()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->storedSetting->value = $value;

        $this->cacheHandler->shouldReceive('has')
                           ->with($key)
                           ->once()
                           ->andReturn(false);

        $this->cacheHandler->shouldReceive('set')
                           ->withArgs(array($key, $this->storedSetting->value))
                           ->once()
                           ->andReturn(true);

        $this->persistentHandler->shouldReceive('get')
                                ->with($key)
                                ->once()
                                ->andReturn($this->storedSetting);

        $result = $this->cachedSettings->get($key);

        $this->assertEquals($value, $result);
    }

    public function testGetReturnsKeyFromPersistentStorageIfCacheDisabled()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->storedSetting->value = $value;

        $this->cachedSettings = new LaravelCachedSettings(
                        'local',
                        false,
                        $this->cacheHandler,
                        $this->persistentHandler
        );

        $this->persistentHandler->shouldReceive('get')
                                ->with($key)
                                ->once()
                                ->andReturn($this->storedSetting);

        $result = $this->cachedSettings->get($key);

        $this->assertEquals($value, $result);
    }

    public function testGetReturnsFalseIfCannotBeFound()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->cacheHandler->shouldReceive('has')
                           ->with($key)
                           ->once()
                           ->andReturn(false);

        $this->persistentHandler->shouldReceive('get')
                                ->with($key)
                                ->once()
                                ->andReturn(false);

        $result = $this->cachedSettings->get($key);

        $this->assertFalse($result);
    }

    public function testDeleteRemovesKeyFromCacheAndPersistentStorage()
    {
        $key = 'my_key';

        $this->cacheHandler->shouldReceive('delete')
                           ->with($key)
                           ->once()
                           ->andReturn(true);

        $this->persistentHandler->shouldReceive('delete')
                                ->with($key)
                                ->once()
                                ->andReturn(true);

        $result = $this->cachedSettings->delete($key);

        $this->assertTrue($result);
    }

    public function testDeleteAllRemovesAllKeysFromCacheAndPersistentStorage()
    {
        $keys = array('key1', 'key2','key3');

        $this->cacheHandler->shouldReceive('delete')
                           ->with($keys[0])
                           ->once()
                           ->andReturn(true);

        $this->cacheHandler->shouldReceive('delete')
                           ->with($keys[1])
                           ->once()
                           ->andReturn(true);

        $this->cacheHandler->shouldReceive('delete')
                           ->with($keys[2])
                           ->once()
                           ->andReturn(true);

        $this->persistentHandler->shouldReceive('getKeys')
                                ->once()
                                ->andReturn($keys);

        $this->persistentHandler->shouldReceive('deleteAll')
                                ->once()
                                ->andReturn(true);

        $result = $this->cachedSettings->deleteAll();

        $this->assertTrue($result);
    }

    public function testHasChecksIfKeyIsInPersistentStorage()
    {
        $key = 'my_key';

        $this->persistentHandler->shouldReceive('has')
                                ->with($key)
                                ->once()
                                ->andReturn(false);

        $result = $this->cachedSettings->has($key);

        $this->assertFalse($result);
    }

    public function testRefreshUpdatesTheValueInCache()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->storedSetting->value = $value;

        $this->cacheHandler->shouldReceive('set')
                           ->withArgs(array($key, $this->storedSetting->value))
                           ->once()
                           ->andReturn(true);

        $this->persistentHandler->shouldReceive('get')
                                ->with($key)
                                ->once()
                                ->andReturn($this->storedSetting);

        $result = $this->cachedSettings->refresh($key);

        $this->assertTrue($result);
    }

    public function testRefreshDoesNotUpdateValueInCacheIfCacheDisabled()
    {
        $key = 'my_key';
        $value = 'my_value';

        $this->storedSetting->value = $value;

        $this->cachedSettings = new LaravelCachedSettings(
                        'local',
                        false,
                        $this->cacheHandler,
                        $this->persistentHandler
        );

        $this->persistentHandler->shouldReceive('get')
                                ->with($key)
                                ->once()
                                ->andReturn($this->storedSetting);

        $result = $this->cachedSettings->refresh($key);

        $this->assertTrue($result);
    }

    public function testRefreshReturnsFalseIfTheValueToBeRefreshedDoesNotExists()
    {
        $key = 'my_key';

        $this->persistentHandler->shouldReceive('get')
                                ->with($key)
                                ->once()
                                ->andReturn(false);

        $result = $this->cachedSettings->refresh($key);

        $this->assertFalse($result);
    }

    public function testRefreshAllReturnsTrueIfCacheDisabled()
    {
        $this->cachedSettings = new LaravelCachedSettings(
                        'local',
                        false,
                        $this->cacheHandler,
                        $this->persistentHandler
        );

        $result = $this->cachedSettings->refreshAll();

        $this->assertTrue($result);
    }

    public function testRefreshAllUpdatesAllKeysInCache()
    {
        $setting1 = new StdClass();
        $setting1->key = 'key1';
        $setting1->value = 'value1';

        $setting2 = new StdClass();
        $setting2->key = 'key2';
        $setting2->value = 'value2';

        $settings = array($setting1, $setting2);

        $this->persistentHandler->shouldReceive('getAll')
                                ->once()
                                ->andReturn($settings);

        $this->cacheHandler->shouldReceive('set')
                           ->withArgs(array($setting1->key, $setting1->value))
                           ->once()
                           ->andReturn(true);

        $this->cacheHandler->shouldReceive('set')
                           ->withArgs(array($setting2->key, $setting2->value))
                           ->once()
                           ->andReturn(true);

        $result = $this->cachedSettings->refreshAll();

        $this->assertTrue($result);
    }
}