<?php


namespace App\Services\Esync\Repositories;


use App\Models\Handbook;
use App\Services\Esync\Contracts\HandbookEntityRepositoryInterface;
use App\Services\Esync\Entities\HandbookEntity;
use App\Services\Esync\EntityList;

class HandbookEntityRepository extends AbstractEntityRepository
{
    /**
     * @return EntityList|HandbookEntity[]
     */
    public function getAll(): EntityList
    {
        return $this->driver->getHandbookList();
    }

    public function getByExternalId(string $externalId): HandbookEntity
    {
        return $this->driver->getHandbook($externalId);
    }
}