<?php

namespace Patkruk\LaravelCachedSettings;

use Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface;
use Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface;

/**
 * LaravelCachedSettings Class.
 *
 * Provides an interface for storing key-value pairs in the caching system and
 * some sort of persistent storage (e.g. database).
 *
 * IT'S IMPORTANT TO UNDERSTAND THAT THE CURRENT APPLICATION ENVIRONMENT IS USED
 * TO GROUP STORED SETTINGS. FOR EXAMPLE. A SETTING STORED WHILE RUNNING IN "local"
 * ENVIRONMENT, WON'T BE AVAILABLE IN A DIFFERENT ENVIRONMENT, SUCH AS "testing"
 * OR "production".
 *
 * @author  Patryk Kruk <patkruk@gmail.com>
 * @package Patkruk\LaravelCachedSettings
 * @copyright  Copyright (c) 2014
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class LaravelCachedSettings
{
    /**
     * Application environment
     * @var string
     */
    protected $env;

    /**
     * Cache handler
     * @var Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface
     */
    protected $cacheHandler;

    /**
     * Persistent storage handler
     * @var Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface
     */
    protected $persistentHandler;

    /**
     * Class constructor method.
     *
     * @param string                     $env
     * @param string                     $cacheEnabled
     * @param CacheHandlerInterface      $cacheHandler
     * @param PersistentHandlerInterface $persistentHandler
     */
    public function __construct($env, $cacheEnabled, CacheHandlerInterface $cacheHandler, PersistentHandlerInterface $persistentHandler)
    {
        $this->env = $env;

        if ($cacheEnabled === true) {
            $this->cacheHandler = $cacheHandler;
        }

        $this->persistentHandler = $persistentHandler;
    }

    /**
     * Sets a setting.
     *
     * @param   string $key
     * @param   string $value
     * @return  boolean
     */
    public function set($key, $value)
    {
        // store in cache if possible
        if (isset($this->cacheHandler)) $this->cacheHandler->set($key, $value);

        if ($this->persistentHandler->has($key)) {
            // update the persistent storage
            return (boolean) $this->persistentHandler->update($key, $value);
        }

        // add to the persistent storage
        return (boolean) $this->persistentHandler->add($key, $value);
    }

    /**
     * Returns a setting.
     *
     * @param  string $key
     * @return string|false
     */
    public function get($key)
    {
        // use cache if possible
        if (isset($this->cacheHandler) && $this->cacheHandler->has($key)) return $this->cacheHandler->get($key);

        // use the persistent storage
        if ($result = $this->persistentHandler->get($key)) {
            // update cache if possible
            if (isset($this->cacheHandler)) $this->cacheHandler->set($key, $result->value);

            return (string) $result->value;
        }

        return false;
    }

    /**
     * Deletes a setting.
     *
     * @param  string $key
     * @return boolean
     */
    public function delete($key)
    {
        // remove from cache
        if (isset($this->cacheHandler)) $this->cacheHandler->delete($key);

        return (boolean) $this->persistentHandler->delete($key);
    }

    /**
     * Deletes all settings for the current environment.
     *
     * @return boolean
     */
    public function deleteAll()
    {
        // get the list of all keys
        $keys = $this->getKeys();

        // erase cache
        if (isset($this->cacheHandler)) {
            foreach ($keys as $key) {
                $this->cacheHandler->delete($key);
            }
        }

        // erase the persistent storage
        return $this->persistentHandler->deleteAll();
    }

    /**
     * Checks if a setting exists in the persistent storage.
     * It does not check if it exists in cache.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        return (boolean) $this->persistentHandler->has($key);
    }

    /**
     * Refresh a setting in cache by updating it with the value from
     * the persistent storage.
     *
     * @param  string $key
     * @return boolean
     */
    public function refresh($key)
    {
        // use the persistent storage
        if ($result = $this->persistentHandler->get($key)) {
            // update cache if possible
            if (isset($this->cacheHandler)) $this->cacheHandler->set($key, $result->value);

            return true;
        }

        return false;
    }

    /**
     * Updates all settings in cache withthe values from
     * the persistent storage for the current environment.
     *
     * @return boolean
     */
    public function refreshAll()
    {
        // get all settings from the persistent storage
        // and then update them in the caching system
        if (isset($this->cacheHandler)) {
            $settings = $this->persistentHandler->getAll();

            foreach ($settings as $setting) {
                $this->cacheHandler->set($setting->key, $setting->value);
            }
        }

        return true;
    }

    /**
     * Returns an array of all setting key names currently kept
     * in the persistent storage for the current environment.
     *
     * @return array
     */
    public function getKeys()
    {
        return (array) $this->persistentHandler->getKeys();
    }

    /**
     * Returns an array of all settings currently kept
     * in the persistent storage for the current environment.
     *
     * @return array
     */
    public function getAll()
    {
        return (array) $this->persistentHandler->getAll();
    }
}
