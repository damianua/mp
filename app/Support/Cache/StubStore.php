<?php


namespace App\Support\Cache;


use Illuminate\Cache\ArrayStore;

/**
 * Class StubStore
 * @package App\Support\Cache
 *
 * Класс реализующий заглушку для механизма кеширования.
 * Работает аналогично ArrayStore, но выделен в отдельный класс для изоляции значений, кеширующих с помощью
 * оригинального ArrayStore
 */
class StubStore extends ArrayStore
{
}