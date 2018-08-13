<?php

namespace Taxusorg\Permission\Contracts;


interface VerifiableInterface
{
    /**
     * @param $permission
     * @return bool
     */
    public function check($permission) : bool;

    /**
     * @param $permission
     * @return bool
     */
    public function can($permission) : bool;

    /**
     * @param $permission
     * @return true
     * @throws \Taxusorg\Permission\Exceptions\AccessDeniedException
     */
    public function allowsOrFail($permission);
}
