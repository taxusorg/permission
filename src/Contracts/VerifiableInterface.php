<?php

namespace Taxusorg\Permission\Contracts;


interface VerifiableInterface
{
    /**
     * @param $permission
     * @return bool
     */
    public function check(string $permission) : bool;

    /**
     * @param $permission
     * @return bool
     */
    public function can(string $permission) : bool;

    /**
     * @param string $permission
     * @return bool
     */
    public function allows(string $permission) : bool;

    /**
     * @param $permission
     * @return true
     * @throws \Taxusorg\Permission\Exceptions\AccessDeniedException
     */
    public function allowsOrFail(string $permission) : bool;
}
