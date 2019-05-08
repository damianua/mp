<?php


namespace App\Services\Esync\Repositories;


use App\Services\Esync\Entities\PropertyEntity;

class ProductPropertyEntityRepository extends AbstractEntityRepository
{
    /**
     * @return \App\Services\Esync\EntityList|PropertyEntity[]
     */
    public function getAll()
    {
        return $this->driver->getProductPropertyList();
    }
}