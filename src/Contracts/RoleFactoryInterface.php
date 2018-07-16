<?php

namespace Taxusorg\Permission\Contracts;

interface RoleFactoryInterface
{
    public function getRole($key);

    public function getRoleByName($name);
}
