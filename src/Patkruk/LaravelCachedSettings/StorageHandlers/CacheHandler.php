<?php

namespace Patkruk\LaravelCachedSettings\StorageHandlers;

use Illuminate\Cache\CacheManager;
use Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface;

/**
 * CacheHandler Class.
 *
 * Allows to persist and retrieve setting entries from the caching system.
 *
 * @author  Patryk Kruk <patkruk@gmail.com>
 * @package Patkruk\LaravelCachedSettings
 * @copyright  Copyright (c) 2014
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class CacheHandler implements CacheHandlerInterface
{
    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * Application environment
     * @var string
     */
    protected $env;

    /**
     * Cache key prefix
     * @var string
     */
    protected $prefix;

    /**
     * Locally cached settings
     * @var array
     */
    protected $settings = array();

    /**
     * Class constructor method.
     *
     * @param   CacheManager $cache
     * @param   string       $env
     * @param   string       $prefix
     * @return  void
     */
    public function __construct(CacheManager $cache, $env, $prefix)
    {
        $this->cache  = $cache;
        $this->env    = $env;
        $this->prefix = $prefix;
    }

    /**
     * Adds/updates a setting.
     *
     * @param   string $key
     * @param   string $value
     * @return  void
     */
    public function set($key, $value)
    {
        // update the local cache
        $this->settings[$key] = $value;

        $this->cache->put($this->key($key), $value, 0);
    }

    /**
     * Returns a setting.
     *
     * @param  string $key
     * @return string
     */
    public function get($key)
    {
        // if the key is cached locally, no need to hit the caching layer
        if (isset($this->settings[$key])) return $this->settings[$key];

        return (string) $this->settings[$key] = $this->cache->get($this->key($key));
    }

    /**
     * Deletes a setting.
     *
     * @param  string $key
     * @return void
     */
    public function delete($key)
    {
        // erase the local cache
        unset($this->settings[$key]);

        $this->cache->forget($this->key($key));
    }

    /**
     * Checks if a setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        return (bool) $this->cache->has($this->key($key));
    }

    /**
     * Builds a key name.
     *
     * @param  string $key
     * @return string
     */
    protected function key($key)
    {
        return (string) $this->prefix . '-' . (string) $this->env . (string) $key;
    }
}
