<?php

namespace Taxusorg\Permission\Contracts;

use IteratorAggregate;

interface ResourceCollectionInterface extends IteratorAggregate
{

    /**
     * @return ResourceInterface
     */
    public function pop();

    /**
     * @param $key
     * @return ResourceInterface
     */
    public function pull($key);

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
