<?php

namespace Taxusorg\Permission;

use Illuminate\Support\Collection;

class PermissionCollection extends Collection implements PermissionInterface
{
    public function allows(RoleInterface $role = null)
    {
        foreach ($this as $item) {
            if ($item->check($role))
                return true;
        }

        return false;
    }

    public function getKeys()
    {
        return $this->pluck('key')->unique();
    }

    public function keys()
    {
        return $this->getKeys();
    }
}
