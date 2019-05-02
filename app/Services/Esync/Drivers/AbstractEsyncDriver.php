<?php


namespace App\Services\Esync\Drivers;


use App\Services\Esync\Contracts\DriverInterface;

abstract class AbstractEsyncDriver implements DriverInterface
{
    /**
     * @var array
     */
    protected $pageParams;

    public function page(int $page, int $pageSize = 10)
    {
        $this->pageParams = [$page, $pageSize];

        return $this;
    }
}