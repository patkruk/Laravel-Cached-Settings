<?php

namespace Patkruk\LaravelCachedSettings\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Patkruk\LaravelCachedSettings\LaravelCachedSettings;

class CachedSettingsRefreshAll extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cached-settings:refresh-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all settings in cache';

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
        if ($this->confirm('Do you want to refresh all cached settings? [yes|no]')) {
            $result = $this->cachedSettings->refreshAll();

            if ($result) {
                $this->line('');
                $this->info("Cached settings refreshed.");
                $this->line('');

                return true;
            }

            $this->line('');
            $this->error("Something went wrong. Couldn't refresh.");
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
