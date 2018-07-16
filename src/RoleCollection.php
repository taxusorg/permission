<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Contracts\RoleCollectionInterface;
use Taxusorg\Permission\Contracts\RoleInterface;

class RoleCollection implements RoleCollectionInterface
{
    protected $roles = [];

    public function push(RoleInterface $role)
    {
        $this->roles[] = $role;
    }

    public function pop()
    {
        return array_pop($this->roles);
    }

    public function prepend(RoleInterface $role, $key = null)
    {
        array_prepend($this->roles, $role, $key);
    }

    public function pull($key = null)
    {
        return array_pull($this->roles, $key, null);
    }

    public function check($permission)
    {
        // TODO: Implement check() method.
    }

    public function can($permission)
    {
        // TODO: Implement can() method.
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->roles);
    }
}
