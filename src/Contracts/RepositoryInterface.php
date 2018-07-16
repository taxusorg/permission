<?php

namespace Taxusorg\Permission\Contracts;

interface RepositoryInterface
{
    /**
     * @param $key
     * @return ResourceInterface
     */
    public function find($key);

    /**
     * @param array $keys
     * @return ResourceCollectionInterface
     */
    public function finds(array $keys);

    /**
     * @param $name
     * @return ResourceInterface
     */
    public function getByName($name);
}
