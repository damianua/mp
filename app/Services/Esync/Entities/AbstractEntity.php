<?php


namespace App\Services\Esync\Entities;


abstract class AbstractEntity
{
    public function __construct(array $fields)
    {
        $this->fill($fields);
    }

    public function fill($fields)
    {
        foreach($fields as $fieldKey => $fieldValue){
            if(property_exists($this, $fieldKey)){
                $this->{$fieldKey} = $fieldValue;
            }
        }

        return $this;
    }
}