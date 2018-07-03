<?php

namespace Taxusorg\Permission\Contracts;

interface RoleRepositoryInterface
{
    public function find($key);

    public function getByName($name);
}
