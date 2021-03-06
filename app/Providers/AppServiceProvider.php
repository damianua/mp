<?php

namespace App\Providers;

use App\Services\EsyncService;
use App\Services\Stateless\CategoryService;
use App\Services\Stateless\HandbookItemService;
use App\Services\Stateless\HandbookService;
use App\Services\Stateless\ProductPropertyService;
use App\Support\Cache\StubStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }


        $this->app->singleton('HandbookService', function(){
            return new HandbookService();
        });
        $this->app->singleton('HandbookItemService', function(){
            return new HandbookItemService();
        });
        $this->app->singleton('CategoryService', CategoryService::class);
        $this->app->singleton('ProductPropertyService', ProductPropertyService::class);
        $this->app->singleton('EsyncService', function($app){
            return new EsyncService(
                $app['EsyncHandbookEntityRepository']
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require app_path('helpers.php');

        Cache::extend('stub', function ($app){
            return Cache::repository(new StubStore);
        });
    }
}
