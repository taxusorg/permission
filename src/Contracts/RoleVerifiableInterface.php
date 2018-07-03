<?php

namespace Taxusorg\Permission\Contracts;

interface RoleVerifiableInterface
{
    public function check($permission);

    public function can($permission);
}
