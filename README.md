Laravel Cached Settings
=============
[![Build Status](https://travis-ci.org/patkruk/Laravel-Cached-Settings.png)](https://travis-ci.org/patkruk/Laravel-Cached-Settings.png)

Provides a basic container for your configuration parameters and settings.

Key-value pairs are stored in your database and, if cache is enabled in the package configuration file, also in your caching system. When you try to retrieve a value from the container, the caching system is always checked first and if the value exists, the database layer is never touched. In case the value is not in your cache, it's retrieved from the persistent storage and also automatically added to the caching layer.

The package uses the current environment name to oraganize settings in order to allow you have different values based on the environment the application is running in. Therefore, a value added while in "local", won't be available in "production" or "testing".

One of the artisan commands the package offers, allows you to import a json file into the persistant storage system. See below for more info.

Installation
===========

Add the package to your composer.json file:

```
"require": {
  "patkruk/laravel-cached-settings": "dev-master"
}
```

Use composer to install the package:

```
$ composer update
```

Pusblish a configuration file using artisan:

```
$ php artisan config:publish patkruk/laravel-cached-settings
```

## Configuration

### Registering the Package

Add an alias to the bottom of app/config/app.php

```php
'CachedSettings' => 'Patkruk\LaravelCachedSettings\Facades\CachedSettings',
```

and register this service provider at the bottom of the `$providers` array:

```php
'Patkruk\LaravelCachedSettings\LaravelCachedSettingsServiceProvider',
```

### Running Migrations

```
$ php artisan migrate --package=patkruk/laravel-cached-settings
```

You can specify the table name in the published package config file.

## Usage

### Adding a new setting:


```php
CachedSettings::set('datetime_format', 'Y-m-d H-i-s');
```

You can use "dot" notation to imitate a multi-dimensional array:

```php
CachedSettings::set('email.admin', 'admin@example.com');
CachedSettings::set('email.editor', 'editor@example.com');
```

### Retrieving a setting:

```php
CachedSettings::get('datetime_format');
CachedSettings::get('email.editor');
CachedSettings::get('email.host', 'default_value');
```

### Checking if a setting exists:

```php
CachedSettings::has('email.admin');
```

It checks the persistent storage and returns a boolean.


### Updating a setting:

```php
CachedSettings::set('email.admin', 'administrator@example.com');
```

### Deleting a setting:

```php
CachedSettings::delete('email.admin');
```

This command removes a setting from the caching and persistent storages!

### Deleting all settings:

```php
CachedSettings::deleteAll();
```

This command removes all settings from the caching and persistent storages!

### Refreshing a setting in cache:

If you have changed a value directly in the database or just want to make sure that your cache is up-to-date,
you can refresh individual settings.

```php
CachedSettings::refresh('email.admin');
```

The value in your cache is updated with the one from the database.

### Refreshing all settings in cache:

You can update your cache with the values from the database with just one command.

```php
CachedSettings::refreshAll();
```

### Getting a list of all keys:

```php
CachedSettings::getKeys();
```

This command returns an array of all keys currently stored in the database.

### Getting all key and settings:

```php
CachedSettings::getKeysAndValues();
```

This command returns an associative array of all settings.

### Getting all settings (table dump):

```php
CachedSettings::getAll();
```

This command returns a dump of the entire table.

## Artisan Commands

The packages provides 5 different artisan commands for your convenience:

### Setting a parameter:

```
$ php artisan cached-settings:set email.admin admin@example.com
```

Or simply run the command below and provide the needed info when asked:

```
$ php artisan cached-settings:set
```

You can always specify the environment by using the "env" option:

```
$ php artisan cached-settings:set email.admin admin@example.com --env=production
```
### Returning a parameter:

```
$ php artisan cached-settings:get email.admin
```

Or simply:

```
$ php artisan cached-settings:get
```

### Refreshing all parameters in cache:

```
$ php artisan cached-settings:refresh-all
```

### Deleting all parameters (cache and database):

```
$ php artisan cached-settings:delete-all
```

### Importing a JSON file:

```
$ php artisan cached-settings:import-file /home/vagrant/import_data.json
```

This command allows you to import a file with a JSON object which has string or number fields only. Example:

```
{
    "email.admin": "admin@example.com",
    "email.editor": "editor@example.com",
    "email.send": "false",
    "security.min_password_length": 15
}
```
As always, you can specify the environment by using the "env" option:

```
$ php artisan cached-settings:import-file /home/vagrant/import_data.json --env=production
```

## License

Laravel Cached Settings is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
