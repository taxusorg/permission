<?php

namespace Taxusorg\Permission\Contracts;

use Taxusorg\Permission\Exceptions\AccessDeniedException;

interface RoleVerifiableInterface
{
    /**
     * @param $permission
     * @return bool
     */
    public function check($permission);

    /**
     * @param $permission
     * @return bool
     */
    public function can($permission);

    /**
     * @param $permission
     * @return true
     * @throws AccessDeniedException
     */
    public function allowsOrFail($permission);
}
