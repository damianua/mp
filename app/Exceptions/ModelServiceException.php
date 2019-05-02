<?php


namespace App\Exceptions;


use App\Models\BaseModel;

class ModelServiceException extends MarketplaceException
{
    private $model;

    /**
     * @param BaseModel | int $model
     * @return ModelServiceException
     */
    public function setModel($model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

}