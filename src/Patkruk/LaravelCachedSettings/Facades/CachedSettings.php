<?php

namespace Patkruk\LaravelCachedSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *  Facade for the Cached Settings package.
 */
class CachedSettings extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'cachedsettings';
    }
}
