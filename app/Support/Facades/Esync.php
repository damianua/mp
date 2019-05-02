<?php


namespace App\Support\Facades;


use Illuminate\Support\Facades\Facade;

class Esync extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'EsyncService';
    }

}