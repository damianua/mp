<?php


namespace App\Services\Esync\Drivers;


use App\Services\Esync\Contracts\DriverInterface;
use App\Services\Esync\Exceptions\HttpDriverException;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Support\Arr;

abstract class AbstractHttpDriver extends AbstractEsyncDriver
{
    const HTTP_STATUS_OK = 200;

    const ERR_INVALID_STATUS = 101;
    const ERR_INVALID_BODY = 102;

    /**
     * @var array
     */
    private $resources;

    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    protected function getResources($key = null)
    {
        return $key ? Arr::get($this->resources, $key) : $this->resources;
    }

    protected function setUpResource($resource)
    {
        $resourceInfo = parse_url($resource);
        $params = isset($resourceInfo['query'])
            ? explode('&', $resourceInfo['query'])
            : [];
        if($this->pageParams){
            list($page, $pageSize) = $this->pageParams;
            $params[] = 'page='.$page;
            $params[] = 'limit='.$pageSize;
            unset($this->pageParams);
        }
        $resourceInfo['query'] = implode('&', $params);
        $resource = http_build_url($resourceInfo);

        return $resource;
    }

    /**
     * @param string $body
     * @param string|null $message
     * @throws HttpDriverException
     */
    protected function throwInvalidBodyException(string $body, string $message = null): void
    {
        $message = $message ?? 'Invalid response data';

        throw (new HttpDriverException($message))->setBody($body);
    }

    /**
     * @param int $status
     * @throws HttpDriverException
     */
    protected function throwInvalidStatusException(int $status): void
    {
        throw (new HttpDriverException(
            'Invalid http response status ('.$status.')'
        ))->setStatus($status);
    }
}