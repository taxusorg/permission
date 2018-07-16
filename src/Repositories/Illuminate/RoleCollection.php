<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Database\Eloquent\Collection;
use Taxusorg\Permission\Contracts\ResourceCollectionInterface;
use Taxusorg\Permission\Contracts\ResourceInterface;

class RoleCollection implements ResourceCollectionInterface
{
    protected $collection;

    public function __construct($items = [])
    {
        $this->collection = new Collection($items);
    }

    public function push(ResourceInterface $resource)
    {
        return $this->collection->push($resource);
    }

    public function pop()
    {
        return $this->collection->pop();
    }

    public function prepend(ResourceInterface $resource, $key)
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
