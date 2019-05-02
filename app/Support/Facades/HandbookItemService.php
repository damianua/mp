<?php


namespace App\Support\Facades;


use Illuminate\Support\Facades\Facade;

class HandbookItemService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'HandbookItemService';
    }
}