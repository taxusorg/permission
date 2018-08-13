<?php

namespace Taxusorg\Permission\Contracts;

interface ResourceInterface extends ResourceSyncInterface
{
    public function getKey();

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return array
     */
    public function getPermits() : array;
}
