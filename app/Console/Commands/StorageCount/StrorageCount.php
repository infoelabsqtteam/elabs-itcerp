<?php

namespace App\Console\Commands\StorageCount;

use Illuminate\Console\Command;

class StrorageCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:StorageCount.StorageCount {path : Enter your path} {--F,--folder | Enter folder name if you want to count sub folders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count folder and files from given path';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path       = $this->argument('path');
        $dir        =  base_path($path) . '/';
        $folders    = count(glob($dir.'*',GLOB_ONLYDIR)) ?? 0;
        $files      = glob($dir.'*');
        $filesCount = count(array_filter($files,'is_file')) ?? 0;
        $this->info('ITC-Lab '.$path.' folder has total '.$folders.' directory and total '.$filesCount.' files.');
    }
}
