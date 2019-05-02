<?php

namespace App\Console\Commands;

use App\Support\Facades\Esync;
use App\Support\Facades\HandbookService;
use Illuminate\Console\Command;

class EsyncHandbookItems extends Command
{
    protected $signature = 'esync:handbook-items {handbook?} {--external}';

    protected $description = 'Sync handbook items list';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $handbookId = $this->argument('handbook');
        if(!$handbookId){
            $this->syncAllHandbookItems();
        }
        else{
            $this->syncByHandbookId($handbookId, $this->option('external'));
        }
    }

    private function syncAllHandbookItems()
    {
        $this->line('Sync all handbooks items...');
        Esync::syncAllHandbookItems();
        $this->info('All items have been successfully synced');
    }

    private function syncByHandbookId($handbookId, $isExternalId)
    {
        $handbook = $isExternalId
            ? HandbookService::getByExternalId($handbookId)
            : HandbookService::findHandbookOrFail($handbookId);

        $this->line(
            'Try to sync handbook with '.($isExternalId ? 'id = ' : 'external id = ').$handbookId
        );

        if($handbook){
            $this->line('Sync...');
            Esync::syncHandbookItems($handbook);
            $this->info('Handbook has been successfully synced');
        }
        else {
            $this->warn('Handbook not found');
        }
    }
}
