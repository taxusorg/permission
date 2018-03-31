<?php

namespace Taxusorg\Permission;

use Illuminate\Support\Collection;

class RoleCollection extends Collection implements RoleInterface
{
    /**
     * @return Collection|static
     */
    public function permitKeys()
    {
        return $this->pluck('permit_keys')->flatten(1)->unique();
    }

    /**
     * @param $permission
     * @return bool
     */
    public function allows($permission)
    {
        foreach ($this as $item) {
            if ($item->allows($permission))
                return true;
        }

        return false;
    }
}
