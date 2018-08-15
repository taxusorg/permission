<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Database\Eloquent\Collection;
use Taxusorg\Permission\Contracts\ResourceInterface;
use Taxusorg\Permission\Contracts\ResourceCollectionInterface;
use Taxusorg\Permission\Exceptions\TypeError;

class ResourceCollection implements ResourceCollectionInterface
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

    public function pop() : ResourceInterface
    {
        return $this->collection->pop();
    }

    public function prepend(ResourceInterface $resource, $key)
    {
        return $this->collection->prepend($resource, $key);
    }

    public function pull($key) : ResourceInterface
    {
        return $this->collection->pull($key);
    }

    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * @param iterable $permissions
     * @return bool|void
     * @throws TypeError
     */
    public function attach($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

        $this->collection->each(function (ResourceInterface $resource) use ($permissions) {
            $resource->attach($permissions);
        });
    }

    /**
     * @param iterable $permissions
     * @return bool|void
     * @throws TypeError
     */
    public function detach($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

        $this->collection->each(function (ResourceInterface $resource) use ($permissions) {
            $resource->detach($permissions);
        });
    }

    /**
     * @param iterable $permissions
     * @return bool|void
     * @throws TypeError
     */
    public function sync($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

        $this->collection->each(function (ResourceInterface $resource) use ($permissions) {
            $resource->sync($permissions);
        });
    }

    /**
     * @param iterable $permissions
     * @return bool|void
     * @throws TypeError
     */
    public function toggle($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

        $this->collection->each(function (ResourceInterface $resource) use ($permissions) {
            $resource->toggle($permissions);
        });
    }

    public function __call($name, $arguments)
    {
        return $this->collection->$name(...$arguments);
    }
}
