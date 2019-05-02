<?php


namespace App\Services\Esync\Exceptions;


use App\Exceptions\MarketplaceException;

class HttpDriverException extends MarketplaceException
{
    /**
     * @var string
     */
    private $body;
    /**
     * @var int
     */
    private $status;

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}