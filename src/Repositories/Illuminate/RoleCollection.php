<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Database\Eloquent\Collection;
use Taxusorg\Permission\Contracts\RoleResourceCollectionInterface;
use Taxusorg\Permission\Contracts\RoleResourceInterface;

class RoleCollection implements RoleResourceCollectionInterface
{
    protected $collection;

    public function __construct($items = [])
    {
        $this->collection = new Collection($items);
    }

    public function push(RoleResourceInterface $resource)
    {
        return $this->collection->push($resource);
    }

    public function pop()
    {
        return $this->collection->pop();
    }

    public function prepend(RoleResourceInterface $resource, $key)
    {
        return $this->collection->prepend($resource, $key);
    }

    public function pull($key)
    {
        return $this->collection->pull($key);
    }

    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    public function __call($name, $arguments)
    {
        return $this->collection->$name(...$arguments);
    }
}
