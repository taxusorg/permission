<?php

namespace Taxusorg\Permission\Contracts;

use IteratorAggregate;

interface RoleResourceCollectionInterface extends IteratorAggregate
{

    /**
     * @return RoleResourceInterface
     */
    public function pop();

    /**
     * @param $key
     * @return RoleResourceInterface
     */
    public function pull($key);

    /**
     * @param RoleResourceInterface $resource
     * @return void
     */
    public function push(RoleResourceInterface $resource);

    /**
     * @param RoleResourceInterface $resource
     * @param $key
     * @return void
     */
    public function prepend(RoleResourceInterface $resource, $key);
}
