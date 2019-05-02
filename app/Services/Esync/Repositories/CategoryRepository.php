<?php


namespace App\Services\Esync\Repositories;


use App\Services\Esync\Entities\CategoryEntity;

class CategoryRepository extends AbstractEntityRepository
{
    /**
     * @return \App\Services\Esync\EntityList|CategoryEntity[]
     */
    public function getAll()
    {
        return $this->driver->getCategoriesList();
    }
}