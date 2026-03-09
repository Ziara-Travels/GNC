<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

class CustomServeCommand extends BaseServeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve:custom 
                            {--host=127.0.0.1 : The host address to serve the application on}
                            {--port=8000 : The port to serve the application on}
                            {--tries=10 : The max number of ports to attempt to serve from}
                            {--no-reload : Do not reload the development server on .env file changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server with unlimited execution time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Set unlimited execution time for CLI
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        
        return parent::handle();
    }
}