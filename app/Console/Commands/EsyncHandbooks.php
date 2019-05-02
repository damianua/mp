<?php

namespace App\Console\Commands;

use App\Support\Facades\Esync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class EsyncHandbooks extends Command
{
    protected $signature = 'esync:handbooks {--withItems} {--flush}';

    protected $description = 'Sync handbooks list';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->flushDataIfNeeded();
        $this->syncHandbooks();
        if($this->option('withItems')){
            Artisan::call('esync:handbook-items', [], $this->getOutput());
        }
    }

    private function flushDataIfNeeded(): void
    {
        if(
            $this->option('flush') &&
            $this->confirm('Do you really want to flush handbooks data?')
        ){
            Esync::flushHandbooks();
            $this->info('Data has been flushed');
        }
    }

    private function syncHandbooks(): void
    {
        $this->line('Sync handbooks...');
        Esync::syncHandbooks();
        $this->info('Handbooks have been synced successful');
    }
}
