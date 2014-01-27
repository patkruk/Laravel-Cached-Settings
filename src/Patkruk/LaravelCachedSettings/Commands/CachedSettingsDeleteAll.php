<?php

namespace Patkruk\LaravelCachedSettings\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Patkruk\LaravelCachedSettings\LaravelCachedSettings;

class CachedSettingsDeleteAll extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cached-settings:delete-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all settings permanently';

    protected $cachedSettings;

    /**
     * Create a new command instance.
     *
     * @param   Application $app
     * @return  void
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
        $this->line('');

        // confirm the action
        if ($this->confirm('Do you want to delete all settings permanently (cache and persistent storage)? [yes|no]')) {
            $result = $this->cachedSettings->deleteAll();

            if ($result) {
                $this->line('');
                $this->info("All settings deleted permanently.");
                $this->line('');

                return true;
            }

            $this->line('');
            $this->error("Something went wrong. Couldn't delete settings.");
            $this->line('');

            return false;
        }

        $this->line('');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
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
