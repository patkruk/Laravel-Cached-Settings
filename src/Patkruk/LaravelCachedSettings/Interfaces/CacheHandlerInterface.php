<?php

namespace Patkruk\LaravelCachedSettings\Interfaces;

interface CacheHandlerInterface
{
    public function set($key, $value);
    public function get($key);
    public function delete($key);
    public function has($key);
}
