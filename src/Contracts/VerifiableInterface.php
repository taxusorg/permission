<?php

namespace Taxusorg\Permission\Contracts;

use Taxusorg\Permission\Exceptions\AccessDeniedException;

interface VerifiableInterface
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
