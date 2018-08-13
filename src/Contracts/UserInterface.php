<?php

namespace Taxusorg\Permission\Contracts;

interface UserInterface
{
    /**
     * @return array
     */
    public function getPermits() : array;

    /**
     * @return ResourceCollectionInterface
     */
    public function getRoles() : ResourceCollectionInterface;
}
