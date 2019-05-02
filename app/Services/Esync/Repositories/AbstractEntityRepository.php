<?php


namespace App\Services\Esync\Repositories;


use App\Services\Esync\Contracts\DriverInterface;

class AbstractEntityRepository
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }
}