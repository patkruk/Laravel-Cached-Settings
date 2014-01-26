<?php

namespace Patkruk\LaravelCachedSettings;

use Illuminate\Cache\CacheManager;
use Patkruk\LaravelCachedSettings\Interfaces\CacheHandlerInterface;

class CacheHandler implements CacheHandlerInterface
{
    protected $cache;

    protected $env;

    protected $prefix;

    public function __construct(CacheManager $cache, $env, $prefix)
    {
        $this->cache  = $cache;
        $this->env    = $env;
        $this->prefix = $prefix;
    }

    public function set($key, $value)
    {
        $this->cache->put($this->key($key), $value, 0);
    }

    public function get($key)
    {
        return $this->cache->get($this->key($key));
    }

    public function delete($key)
    {
        $this->cache->forget($this->key($key));
    }

    public function has($key)
    {
        $this->cache->has($this->key($key));
    }

    protected function key($key)
    {
        return $this->prefix . '-' . $this->env . '' . (string) $key;
    }
}
