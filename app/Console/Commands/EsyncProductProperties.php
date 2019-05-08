<?php

namespace App\Console\Commands;

use App\Support\Facades\Esync;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;

class EsyncProductProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esync:product-properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product properties';

    private $retryAttempts = 1;

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
            $this->syncProductProperties();
        }
        catch (ConnectException $e){
            $this->error($e->getMessage());
            $this->retry();
        }
    }

    private function syncProductProperties()
    {
        $this->line('Sync product properties...');
        Esync::syncProductProperties();
        $this->info('Product properties have been successfully synced');
    }

    private function retry()
    {
        if($this->retryAttempts <= 5){
            $this->info('Retry '.$this->retryAttempts.'/5...');
            $this->handle();
        }
        else{
            $this->warn('Abort');
        }
    }
}
