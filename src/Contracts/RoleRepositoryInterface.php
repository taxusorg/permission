<?php

namespace Taxusorg\Permission\Contracts;

interface RoleRepositoryInterface
{
    /**
     * @param $key
     * @return RoleResourceInterface
     */
    public function find($key);

    /**
     * @param array $keys
     * @return RoleResourceCollectionInterface
     */
    public function finds(array $keys);

    /**
     * @param $name
     * @return RoleResourceInterface
     */
    public function getByName($name);
}
