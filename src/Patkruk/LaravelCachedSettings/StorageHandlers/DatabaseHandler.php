<?php

namespace Patkruk\LaravelCachedSettings;

use Illuminate\Database\DatabaseManager;
use Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface;

class DatabaseHandler implements PersistentHandlerInterface
{
    protected $db;

    protected $env;

    protected $prefix;

    public function __construct(DatabaseManager $db, $env, $prefix)
    {
        $this->db     = $db;
        $this->env    = $env;
        $this->prefix = $prefix;
    }

    public function set($key, $value)
    {

    }

    public function get($key)
    {

    }

    public function delete($key)
    {

    }

    public function has($key)
    {

    }
}
