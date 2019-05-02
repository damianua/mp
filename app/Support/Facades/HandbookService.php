<?php


namespace App\Support\Facades;


use Illuminate\Support\Facades\Facade;

class HandbookService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'HandbookService';
    }
}