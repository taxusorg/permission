<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Contracts\RoleCollectionInterface;
use Taxusorg\Permission\Contracts\RoleInterface;
use Taxusorg\Permission\Exceptions\AccessDeniedException;

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

    /**
     * @param $permission
     * @return bool
     */
    public function check($permission)
    {
        return $this->can($permission);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function can($permission)
    {
        foreach ($this as $item) {
            if ($item->can($permission))
                return true;
        }

        return false;
    }

    /**
     * @param $permission
     * @return true
     * @throws AccessDeniedException
     */
    public function allowsOrFail($permission)
    {
        if (! $this->can($permission))
            throw new AccessDeniedException('Access Denied.');

        return true;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->roles);
    }
}
