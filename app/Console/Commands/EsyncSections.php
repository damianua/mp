<?php

namespace App\Console\Commands;

use App\Support\Facades\Esync;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;

class EsyncSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esync:sections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync catalog sections';

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
        try{
            $this->info('Sync catalog sections...');
            Esync::syncSections();
            $this->info('Catalog sections have been successfully synced');
        }
        catch (ConnectException $e){
            $this->error($e->getMessage());
            $this->info('Retry...');
            $this->handle();
        }
    }
}
