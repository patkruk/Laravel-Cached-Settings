<?php

namespace Patkruk\LaravelCachedSettings\Interfaces;

/**
 * Cache Handler Interface
 */
interface CacheHandlerInterface
{
    /**
     * Adds/updates a setting.
     *
     * @param   string $key
     * @param   string $value
     * @return  void
     */
    public function set($key, $value);

    /**
     * Returns a setting.
     *
     * @param  string $key
     * @return string
     */
    public function get($key);

    /**
     * Deletes a setting.
     *
     * @param  string $key
     * @return void
     */
    public function delete($key);

    /**
     * Checks if a setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key);
}
