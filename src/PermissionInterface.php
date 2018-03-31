<?php

namespace Taxusorg\Permission;

interface PermissionInterface
{
    public function allows(RoleInterface $roles = null);

    public function getKeys();
}
