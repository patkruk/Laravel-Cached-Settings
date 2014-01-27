<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCachedSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tableName = Config::get('laravel-cached-settings::tableName');

		Schema::create($tableName, function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('environment', 20);
			$table->string('key', 255);
			$table->text('value')->nullable();
			$table->integer('updated_timestamp')->unsigned()->nullable();

			// index
			$table->unique(array('environment', 'key'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$tableName = Config::get('laravel-cached-settings::tableName');

		Schema::drop($tableName);
	}

}