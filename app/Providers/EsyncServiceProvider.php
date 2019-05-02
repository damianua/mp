<?php

namespace App\Providers;

use App\Services\Esync\Drivers\GuzzleHttpDriver;
use App\Services\Esync\Repositories\CategoryRepository;
use App\Services\Esync\Repositories\HandbookEntityRepository;
use App\Services\Esync\Repositories\HandbookItemEntityRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class EsyncServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('EsyncDriver', function($app){
            return (new GuzzleHttpDriver(
                new Client(['base_uri' => config('esync.base_uri')]),
                config('esync.resources')
            ))
                ->useLogger(Log::channel('esync'))
                ->useBasicAuth(config('esync.auth.user'), config('esync.auth.password'));
        });

        $this->app->singleton('EsyncHandbookEntityRepository', function($app){
            return new HandbookEntityRepository($app['EsyncDriver']);
        });

        $this->app->singleton('EsyncCategoryRepository', function($app){
            return new CategoryRepository($app['EsyncDriver']);
        });

        $this->app->bind('EsyncHandbookItemEntityRepository', function($app){
            return new HandbookItemEntityRepository($app['EsyncDriver']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
