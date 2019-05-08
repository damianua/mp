<?php


namespace App\Services;


use App\Models\Handbook;
use App\Models\HandbookItem;
use App\Services\Esync\Repositories\HandbookEntityRepository;
use App\Services\Esync\Repositories\HandbookItemEntityRepository;
use App\Services\Stateless\HandbookItemService;
use App\Services\Stateless\HandbookService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EsyncService
{
    /**
     * @var HandbookItemService
     */
    private $handbookItemService;
    /**
     * @var HandbookEntityRepository
     */
    private $handbookEntityRepository;
    /**
     * @var HandbookService
     */
    private $handbookService;
    /**
     * @var Stateless\ProductPropertyService
     */
    private $productPropertyService;

    public function __construct(
        HandbookEntityRepository $handbookEntityRepository
    )
    {
        $this->handbookService = app('HandbookService');
        $this->handbookItemService = app('HandbookItemService');
        $this->handbookEntityRepository = $handbookEntityRepository;
        $this->productPropertyService = app('ProductPropertyService');
    }

    public function flushHandbooks()
    {
        Schema::disableForeignKeyConstraints();
        DB::table(HandbookItem::getTableName())->truncate();
        DB::table(Handbook::getTableName())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function syncHandbooks()
    {
        $handbookEntities = $this->handbookEntityRepository->getAll();
        foreach($handbookEntities as $handbookEntity){
            if(!$handbookEntity->xml_id){
                continue;
            }
            $handbook = $this->handbookService->getByExternalId($handbookEntity->xml_id);
            if($handbook){
                $this->handbookService->updateHandbook($handbook, [
                    'name' => $handbookEntity->title
                ]);
            }
            else{
                $this->handbookService->createHandbook([
                    'name' => $handbookEntity->title,
                    'external_id' => $handbookEntity->xml_id
                ]);
            }
        }
    }

    public function syncAllHandbookItems()
    {
        $handbooks = $this->handbookService->getAll();

        foreach($handbooks as $handbook){
            $this->syncHandbookItems($handbook);
        }
    }

    public function syncHandbookItems($handbook)
    {
        $handbook = $this->handbookService->findHandbookOrFail($handbook);
        /**
         * @var HandbookItemEntityRepository $repository
         */
        $repository = app('EsyncHandbookItemEntityRepository')->setHandbookXmlId($handbook->external_id);
        foreach($repository->getAll(100) as $handbookItemEntity){
            if(!$handbookItemEntity->xmlId){
                continue;
            }
            $handbookItem = $this->handbookItemService->getByExternalId($handbookItemEntity->xmlId);
            if($handbookItem){
                $this->handbookItemService->updateHandbookItem($handbookItem, [
                    'name' => $handbookItemEntity->title,
                    'handbook_id' => $handbook->id
                ]);
            }
            else{
                $this->handbookItemService->createHandbookItem([
                    'name' => $handbookItemEntity->title,
                    'external_id' => $handbookItemEntity->xmlId,
                    'handbook_id' => $handbook->id
                ]);
            }
        }
    }

    public function syncProductProperties()
    {
        $repository = app('EsyncProductPropertyEntityRepository');
        foreach($repository->getAll() as $propertyEntity){
            if(!$propertyEntity->xmlId){
                continue;
            }
            $attributes = [
                'active' => $propertyEntity->active === 'Y',
                'sort' => $propertyEntity->sort,
                'name' => $propertyEntity->title
            ];
            $productProperty = $this->productPropertyService->getByExternalId($propertyEntity->xmlId);
            if($productProperty){
                $this->productPropertyService->updateProductProperty($productProperty, $attributes);
            }
            else{
                $attributes['external_id'] = $propertyEntity->xmlId;
                $this->productPropertyService->createProductProperty($attributes);
            }
        }
    }
}