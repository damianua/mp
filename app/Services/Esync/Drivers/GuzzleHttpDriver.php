<?php


namespace App\Services\Esync\Drivers;

use App\Services\Esync\Entities\CategoryEntity;
use App\Services\Esync\Entities\HandbookEntity;
use App\Services\Esync\Entities\HandbookItemEntity;
use App\Services\Esync\Entities\PropertyEntity;
use App\Services\Esync\EntityList;
use App\Services\Esync\EntityListPager;
use App\Services\Esync\Exceptions\HttpDriverException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Log\LoggerInterface;

class GuzzleHttpDriver extends AbstractHttpDriver
{
    const AUTH_BASIC = 'basic';
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var array
     */
    private $handbookResources;
    private $authParams = [];
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Client $httpClient, array $resources)
    {
        /**
         * @var Uri $uri
         */
        parent::__construct($resources);
        $this->httpClient = $httpClient;
        $this->handbookResources = $this->getResources('handbooks');
    }

    public function useBasicAuth(string $user, string $password): self
    {
        $this->logger->notice('Для накоторых(или всех) запросов используется basic-аутентификация');
        $this->authParams['basic'] = [$user, $password];

        return $this;
    }

    public function useLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param $handbookExternalId
     * @return HandbookItemEntity[]|EntityList
     * @throws HttpDriverException
     */
    public function getHandbookItemList(string $handbookExternalId): EntityList
    {
        $list = [];
        $pager = new EntityListPager();
        if(isset($this->handbookResources[$handbookExternalId])){
            $this->logger->info('Получение данных о значениях справочника "'.$handbookExternalId.'" ...');
            $resource = $this->handbookResources[$handbookExternalId].'/element';
            if($this->pageParams){
                $pager->fill(['page' => $this->pageParams[0], 'pageSize' => $this->pageParams[1]]);
            }
            $response = $this->get($resource);
            $data = $this->getResponseDataOrFail($response);
            $pager->fill(['totalItems' => $data['totalCount'], 'totalPages' => $data['totalPages']]);
            foreach($data['data'] as $handbookItemData){
                $list[] = new HandbookItemEntity($handbookItemData);
            }
        }
        else{
            $this->logger->info("Ресурс для справочника {$handbookExternalId} не зарегистрирован в системе");
        }

        return new EntityList($list, $pager);
    }

    /**
     * @return HandbookEntity[]|EntityList
     * @throws HttpDriverException
     */
    public function getHandbookList(): EntityList
    {
        $list = [];
        $this->logger->info('Получение списка справчоников...');
        foreach($this->handbookResources as $externalId => $handbookResource){
            $list[] = $this->getHandbook($externalId);
        }

        return new EntityList($list);
    }

    /**
     * @param $externalId
     * @return HandbookEntity|null
     * @throws HttpDriverException
     */
    public function getHandbook(string $externalId): HandbookEntity
    {
        $handbookEntity = null;
        if(isset($this->handbookResources[$externalId])){
            $this->logger->info('Получение информации о справочнике "'.$externalId.'" ...');
            $resource = $this->handbookResources[$externalId];
            $response = $this->get($resource);
            $data = current($this->getResponseDataOrFail($response)['data']);
            $handbookEntity = new HandbookEntity($data);
        }
        else{
            $this->logger->info("Ресурс для справочника {$externalId} не зарегистрирован в системе");
        }

        return $handbookEntity;
    }

    public function getProductPropertyList(): EntityList
    {
        $list = [];
        $this->logger->info('Получение списка свойств товара');
        $resource = $this->getResources('product_properties');
        $response = $this->get($resource);
        $data = $this->getResponseDataOrFail($response)['data'];
        foreach($data as $productPropertyData){
            $list[] = new PropertyEntity($productPropertyData);
        }

        return new EntityList($list);
    }

    /**
     * @param Response $response
     * @return array
     * @throws HttpDriverException
     */
    private function getResponseDataOrFail(Response $response):array
    {
        if($response->getStatusCode() === self::HTTP_STATUS_OK){
            $body = json_decode($response->getBody(), true);
            if(!$body){
                $this->throwInvalidBodyException($response->getBody(), 'Response data must be a valid JSON');
            }
            elseif(!is_array($body)){
                $this->throwInvalidBodyException($response->getBody(), 'Response data must be an array');
            }

            return $body;
        }
        else{
            $this->throwInvalidStatusException($response->getStatusCode());
        }
    }

    protected function get(string $resource, string $authType = self::AUTH_BASIC): Response
    {
        $this->logger->info('Настройка ресурса "'.$resource.'" ...');

        $options = [];
        if($authType){
            switch($authType){
                case self::AUTH_BASIC:
                    $options['auth'] = $this->authParams['basic'];
                    break;
            }
        }
        $resource = $this->setUpResource($resource);
        $this->logger->info('Отправка GET-запроса к '.$this->httpClient->getConfig()['base_uri'].$resource.' ...');
        $response = $this->httpClient->get($resource, $options);
        $this->logger->info('Получен ответ от сервера. Код ответа: '.$response->getStatusCode());

        return $response;
    }
}