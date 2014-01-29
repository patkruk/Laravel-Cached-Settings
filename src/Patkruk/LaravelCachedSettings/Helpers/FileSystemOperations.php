<?php

namespace Patkruk\LaravelCachedSettings\Helpers;

/**
 * FileSystemOperations Class
 *
 * @author  Patryk Kruk <patkruk@gmail.com>
 * @package Patkruk\LaravelCachedSettings
 * @copyright  Copyright (c) 2014
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class FileSystemOperations
{
    /**
     * Tells whether a file exists and is readable.
     *
     * @param  string  $file
     * @return boolean
     */
    public function isReadable($file)
    {
        return (bool) is_readable($file);
    }

    /**
     * Reads entire file into a string.
     *
     * @param  string $file
     * @return string
     */
    public function readFile($file)
    {
        return file_get_contents($file);
    }

    /**
     * Decodes a JSON string.
     *
     * @param  string $file
     * @return array
     */
    public function decodeJson($file)
    {
        return json_decode($file, true);
    }
}
