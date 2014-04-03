<?php

namespace Patkruk\LaravelCachedSettings\StorageHandlers;

use Illuminate\Database\DatabaseManager;
use Patkruk\LaravelCachedSettings\Interfaces\PersistentHandlerInterface;

/**
 * DatabaseHandler Class.
 *
 * Allows to persist and retrieve setting entries from the database.
 *
 * @author  Patryk Kruk <patkruk@gmail.com>
 * @package Patkruk\LaravelCachedSettings
 * @copyright  Copyright (c) 2014
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class DatabaseHandler implements PersistentHandlerInterface
{
    /**
     * Updated timestamp field name
     */
    CONST TIMESTAMP_FIELD = 'updated_timestamp';

    /**
     * @var DatabaseManager
     */
    protected $db;

    /**
     * Application environment
     * @var string
     */
    protected $env;

    /**
     * Table name
     * @var string
     */
    protected $tableName;

    /**
     * Locally cached settings
     * @var array
     */
    protected $settings = array();

    /**
     * Class constructor method.
     *
     * @param   DatabaseManager $db
     * @param   string          $env
     * @param   string          $tableName
     * @return  void
     */
    public function __construct(DatabaseManager $db, $env, $tableName)
    {
        $this->db        = $db;
        $this->env       = $env;
        $this->tableName = $tableName;
    }

    /**
     * Returns a setting.
     *
     * @param  string $key
     * @return object
     */
    public function get($key)
    {
        return $this->db->table($this->tableName)
                        ->where('environment', '=', $this->env)
                        ->where('key', '=', (string) $key)
                        ->take(1)
                        ->first();
    }

    /**
     * Returns all settings for the current environment.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->db->table($this->tableName)
                        ->where('environment', '=', $this->env)
                        ->orderBy('key', 'asc')
                        ->get();
    }

    /**
     * Adds a new setting.
     *
     * @param   string $key
     * @param   string $value
     * @return  boolean
     */
    public function add($key, $value)
    {
        $result =  $this->db->table($this->tableName)
                            ->insertGetId(array(
                                'environment'         => $this->env,
                                'key'                 => (string) $key,
                                'value'               => (string) $value,
                                self::TIMESTAMP_FIELD => time()
                            ));

        return ($result) ? true : false;
    }

    /**
     * Updates a setting.
     *
     * @param  string $key
     * @param  string $value
     * @return boolean
     */
    public function update($key, $value)
    {
        $result = $this->db->table($this->tableName)
                           ->where('environment', '=', $this->env)
                           ->where('key', '=', (string) $key)
                           ->update(array(
                                'value'               => (string) $value,
                                self::TIMESTAMP_FIELD => time()
                            ));

        return ($result === 1) ? true : false;
    }

    /**
     * Deletes a setting.
     *
     * @param  string $key
     * @return boolean
     */
    public function delete($key)
    {
        $result = $this->db->table($this->tableName)
                           ->where('environment', '=', $this->env)
                           ->where('key', '=', (string) $key)
                           ->delete();

        return ($result === 1) ? true : false;
    }

    /**
     * Deletes all settings for the current environment.
     *
     * @return boolean
     */
    public function deleteAll()
    {
        $result = $this->db->table($this->tableName)
                           ->where('environment', '=', $this->env)
                           ->delete();

        return (is_integer($result)) ? true : false;
    }

    /**
     * Checks if a key exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        $result = $this->db->table($this->tableName)
                           ->where('environment', '=', $this->env)
                           ->where('key', '=', (string) $key)
                           ->count();

        return ($result == 1) ? true : false;
    }

    /**
     * Returns all keys for the current environment.
     *
     * @return array
     */
    public function getKeys()
    {
        return $this->db->table($this->tableName)
                        ->where('environment', '=', $this->env)
                        ->orderBy('key', 'asc')
                        ->lists('key');
    }
}
