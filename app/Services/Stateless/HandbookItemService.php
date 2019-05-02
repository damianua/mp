<?php


namespace App\Services\Stateless;


use App\Models\HandbookItem;

class HandbookItemService extends AbstractModelService
{
    public function getModelClass(): string
    {
        return HandbookItem::class;
    }

    /**
     * @param $externalId
     * @return HandbookItem|null
     */
    public function getByExternalId($externalId)
    {
        return $this->get(HandbookItem::where('external_id', $externalId))->first();
    }

    public function createHandbookItem(array $attributes)
    {
        $handbookItem = HandbookItem::create($attributes);

        return $handbookItem;
    }

    public function updateHandbookItem($handbookItem, array $attributes)
    {
        $handbookItem = $this->findHandbookItemOrFail($handbookItem);

        return $handbookItem->fill($attributes)->save();
    }

    public function findHandbookItemOrFail($handbookItem)
    {
        return $this->findOrFail($handbookItem);
    }
}