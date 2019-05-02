<?php


namespace App\Services;


use App\Models\Handbook;
use App\Models\HandbookItem;
use App\Services\Esync\Repositories\CategoryRepository;
use App\Services\Esync\Repositories\HandbookEntityRepository;
use App\Services\Esync\Repositories\HandbookItemEntityRepository;
use App\Services\Stateless\HandbookItemService;
use App\Services\Stateless\HandbookService;
use App\Services\Stateless\SectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EsyncService
{
    /**
     * @var SectionService
     */
    protected $sectionService;
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

    public function __construct(
        HandbookEntityRepository $handbookEntityRepository
    )
    {
        $this->handbookService = app('HandbookService');
        $this->handbookItemService = app('HandbookItemService');
        $this->sectionService = app('SectionService');

        $this->handbookEntityRepository = $handbookEntityRepository;
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

    public function syncSections()
    {
        /**
         * @var CategoryRepository $repository
         */
        $repository = app('EsyncCategoryRepository');
        foreach ($repository->getAll() as $categoryEntity){
            $section = $this->sectionService->getByExternalId($categoryEntity->id);
            if($section){
                $this->sectionService->updateSection($section, [
                    'name' => $categoryEntity->title
                ]);
            }
            else{
                $this->sectionService->createSection([
                    'name' => $categoryEntity->title,
                    'external_id' => $categoryEntity->id
                ]);
            }
        }
    }
}