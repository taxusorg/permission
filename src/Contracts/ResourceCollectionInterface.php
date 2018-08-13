<?php

namespace Taxusorg\Permission\Contracts;

use IteratorAggregate;

interface ResourceCollectionInterface extends IteratorAggregate, ResourceSyncInterface
{
    /**
     * @return ResourceInterface
     */
    public function pop() : ResourceInterface;

    /**
     * @param $key
     * @return ResourceInterface
     */
    public function pull($key) : ResourceInterface;

    /**
     * @param ResourceInterface $resource
     * @return void
     */
    public function push(ResourceInterface $resource);

    /**
     * @param ResourceInterface $resource
     * @param $key
     * @return void
     */
    public function prepend(ResourceInterface $resource, $key);
}
