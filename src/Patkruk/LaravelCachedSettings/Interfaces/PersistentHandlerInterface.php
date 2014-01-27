<?php

namespace Patkruk\LaravelCachedSettings\Interfaces;

/**
 * Persistent Handler Interface
 */
interface PersistentHandlerInterface
{
    /**
     * Returns a setting.
     *
     * @param  string $key
     * @return object
     */
    public function get($key);

    /**
     * Returns all settings for the current environment.
     *
     * @return array
     */
    public function getAll();

    /**
     * Adds a new setting.
     *
     * @param   string $key
     * @param   string $value
     * @return  boolean
     */
    public function add($key, $value);

    /**
     * Updates a setting.
     *
     * @param  string $key
     * @param  string $value
     * @return boolean
     */
    public function update($key, $value);

    /**
     * Deletes a setting.
     *
     * @param  string $key
     * @return boolean
     */
    public function delete($key);

    /**
     * Deletes all settings for the current environment.
     *
     * @return boolean
     */
    public function deleteAll();

    /**
     * Checks if a key exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key);

    /**
     * Returns all keys for the current environment.
     *
     * @return array
     */
    public function getKeys();
}
