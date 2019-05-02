<?php


namespace App\Services\Esync\Contracts;


use App\Services\Esync\Entities\HandbookEntity;
use App\Services\Esync\EntityList;

interface DriverInterface
{
    public function getHandbookList(): EntityList;
    public function getHandbook(string $externalId): HandbookEntity;
    public function getHandbookItemList(string $handbookExternalId): EntityList;
    public function getCategoriesList(): EntityList;

    /**
     * @param int $page
     * @param int $pageSize
     * @return $this
     */
    public function page(int $page, int $pageSize = 20);
}