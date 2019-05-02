<?php


namespace App\Services\Esync;


class EntityList implements \ArrayAccess, \Iterator
{
    private $items = [];
    private $pager;

    public function __construct(array $items, EntityListPager $pager = null)
    {
        $this->items = $items;
        $this->pager = $pager ?? new EntityListPager(count($items));
    }

    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }

    public function setPager(EntityListPager $pager)
    {
        $this->pager = $pager;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getPager()
    {
        return $this->pager;
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function current()
    {
        return current($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function valid()
    {
        return isset($this->items[$this->key()]);
    }

    public function rewind()
    {
        reset($this->items);
    }
}