<?php


namespace App\Services\Stateless;


use App\Exceptions\ModelServiceException;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractModelService
{
    private $beforeQuery;

    abstract public function getModelClass(): string;

    public function beforeQuery(callable $func)
    {
        $this->beforeQuery = $func;
    }

    public function cache()
    {
        if($this->beforeQuery){
            return cache()->store('stub');
        }

        return cache();
    }

    public function get(Builder $query, array $columns = ['*'])
    {
        if($this->beforeQuery){
            call_user_func($this->beforeQuery);
            unset($this->beforeQuery);
        }

        return $query->get($columns);
    }

    /**
     * @param BaseModel|int $model
     * @return BaseModel
     * @throws ModelServiceException
     */
    protected function findOrFail($model)
    {
        $modelClass = $this->getModelClass();
        if(
            $model instanceof $modelClass &&
            $model->exists
        ){
            return $model;
        }

        $modelId = (int)$model;
        if(
            ($modelId > 0) &&
            ($result = $modelClass::find($modelId))
        ){
            return $result;
        }

        throw (new ModelServiceException('Model ("'.$this->getModelClass().'") with id = '.$modelId.' not found'))->setModel($model);
    }
}