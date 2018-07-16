<?php

namespace Taxusorg\Permission\Contracts;

interface FactoryInterface
{
    public function getRole($key);

    public function getRoleByName($name);
}
