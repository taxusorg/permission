<?php

namespace Taxusorg\Permission;

interface UserInterface
{
    /**
     * @return RoleInterface
     */
    public function getRoles();
}
