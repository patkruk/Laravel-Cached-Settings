<?php

namespace Patkruk\LaravelCachedSettings\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Patkruk\LaravelCachedSettings\LaravelCachedSettings;

class CachedSettingsImportFile extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cached-settings:import-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a JSON file';

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
        // get the "filepath" argument
        $filePath = $this->argument('filepath');

        // be sure the required arguments are set
        if (empty($filePath)) {
            $this->line('');
            $this->error('Filepath not specified!');

            return false;
        }

        try {
            // read the file and import it
            $result = $this->cachedSettings->importFile($filePath);

            if ($result) {
                $this->line('');
                $this->info("File imported.");
                $this->line('');

                return true;
            }

        } catch (\Exception $e) {
            $this->line('');
            $this->error($e->getMessage());
            $this->line('');

            return false;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('filepath', InputArgument::OPTIONAL, 'File path.'),
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
