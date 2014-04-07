<?php

namespace Patkruk\LaravelCachedSettings\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Patkruk\LaravelCachedSettings\LaravelCachedSettings;

class CachedSettingsSet extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cached-settings:set';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set a setting';

	protected $cachedSettings;

	/**
	 * Create a new command instance.
	 *
	 * @param 	Application $app
	 * @return 	void
	 */
	public function __construct(LaravelCachedSettings $cachedSettings)
	{
		parent::__construct();

		$this->cachedSettings = $cachedSettings;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// get the "key" argument
		$key = $this->argument('key');
		if (empty($key)) {
			$this->line('');
			$key = $this->ask('Setting name:');
		}

		// get the "value" argument
		$value = $this->argument('value');
		if (empty($value)) {
			$this->line('');
			$value = $this->ask('Setting value:');
		}

		// be sure the required arguments are set
		if (empty($key)) {
			$this->line('');
			$this->error('Setting name not specified');

			return false;
		}

		$result = $this->cachedSettings->set($key, $value);

		if ($result) {
			$this->line('');
			$this->info("Setting '$key' added.");
			$this->line('');

			return true;
		}

		$this->line('');
		$this->error("Problem adding setting '$key'.");
		$this->line('');

		return false;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('key', InputArgument::OPTIONAL, 'Setting name.'),
			array('value', InputArgument::OPTIONAL, 'Setting value.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
