<?php

namespace Taxusorg\Permission;

use Illuminate\Support\Collection;

interface RoleInterface
{
    /**
     * @param $permission
     * @return mixed
     */
    public function allows($permission);

    /**
     * @return Collection
     */
    public function permitKeys();
}
