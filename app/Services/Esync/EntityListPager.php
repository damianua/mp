<?php


namespace App\Services\Esync;


class EntityListPager
{
    public $page;
    public $pageSize;
    public $totalPages;
    protected $totalItems;

    public function __construct(int $totalItems = 0, int $totalPages = null, int $page = null, int $pageSize = null)
    {
        $this->page = $page ?? 1;
        $this->pageSize = $pageSize ?? $totalItems;
        $this->totalPages = $totalPages ?? 1;

        $this->setTotalItems($totalItems);
    }

    public function fill(array $params): self
    {
        foreach($params as $key => $value){
            if(property_exists($this, $key)){
                if($key === 'totalItems'){
                    $this->setTotalItems($value);
                }
                else{
                    $this->{$key} = $value;
                }
            }
        }

        return $this;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems):self
    {
        $this->totalItems = $totalItems;
        if(is_null($this->totalPages)){
            $this->totalPages = 1;
        }
        if(is_null($this->page)){
            $this->page = 1;
        }
        if(is_null($this->pageSize)){
            $this->pageSize = $totalItems;
        }

        return $this;
    }
}