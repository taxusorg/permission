<?php

namespace Taxusorg\Permission\Contracts;

interface RoleInterface extends VerifiableInterface, RoleSyncInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return mixed
     */
    public function getKey();

    /**
     * @return array
     */
    public function getPermits() : array;
}
