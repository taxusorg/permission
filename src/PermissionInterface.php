<?php

namespace Taxusorg\Permission;

interface PermissionInterface
{
    public function allows(RoleInterface $roles = null, $throw = false);

    public function getKeys();
}
