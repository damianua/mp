<?php


namespace App\Services\Esync\Repositories;


use App\Services\Esync\Contracts\DriverInterface;
use App\Services\Esync\Entities\HandbookItemEntity;
use App\Services\Esync\EntityList;

class HandbookItemEntityRepository extends AbstractEntityRepository
{
    private $handbookXmlId;

    public function __construct(DriverInterface $driver, $handbookXmlId = null)
    {
        parent::__construct($driver);
        $this->handbookXmlId = $handbookXmlId;
    }

    /**
     * @param $handbookXmlId
     * @return HandbookItemEntityRepository
     */
    public function setHandbookXmlId($handbookXmlId)
    {
        $this->handbookXmlId = $handbookXmlId;

        return $this;
    }

    /**
     * @param int|null $chunked
     * @return EntityList|HandbookItemEntity[]
     */
    public function getAll(int $chunked = 10): EntityList
    {
        $items = [];
        $page = 1;
        do{
            $list = $this->driver->page($page, $chunked)->getHandbookItemList($this->handbookXmlId);
            $items = array_merge($items, $list->getItems());
            $page++;
        }
        while($page <= $list->getPager()->totalPages);

        return new EntityList($items);
    }
}