<?php

namespace Taxusorg\Permission;

use ArrayIterator;
use Taxusorg\Permission\Contracts\RoleCollectionInterface;
use Taxusorg\Permission\Contracts\RoleInterface;

class RoleCollection implements RoleCollectionInterface
{
    protected $roles = [];

    protected $role_names = [];

    public function push(RoleInterface $role)
    {
        $this->roles[$role->getKey()] = $role;
        $this->role_names[$role->getName()] = $role->getKey();
    }

    public function pop() : RoleInterface
    {
        $item = array_pop($this->roles);
        unset($this->role_names[$item->getName()]);

        return $item;
    }

    public function isset($name) : bool
    {
        return key_exists($name, $this->role_names);
    }

    public function check($permission) : bool
    {
        foreach ($this as $item) {
            if ($item->check($permission))
                return true;
        }

        return false;
    }

    public function can($permission) : bool
    {
        return $this->check($permission);
    }

    public function allowsOrFail($permission)
    {
        foreach ($this as $item) {
            if ($item->allowsOrFail($permission))
                return true;
        }

        return false;
    }
    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function attach(...$permissions)
    {
        foreach ($this->roles as $role) {
            $role->attach(...$permissions);
        };

        return true;
    }

    /**
     * @param string|array ...$permissions string
     * @return bool
     */
    public function detach(...$permissions)
    {
        foreach ($this->roles as $role) {
            $role->detach(...$permissions);
        };

        return true;
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function sync(...$permissions)
    {
        foreach ($this->roles as $role) {
            $role->sync(...$permissions);
        };

        return true;
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function toggle(...$permissions)
    {
        foreach ($this->roles as $role) {
            $role->toggle(...$permissions);
        };

        return true;
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->roles);
    }

    /**
     * @param mixed $key
     * @return RoleInterface
     */
    public function offsetGet($key)
    {
        return $this->roles[$key];
    }

    public function offsetSet($key, $value)
    {
        $this->push($value);
    }

    public function offsetUnset($key)
    {
        if (isset($this->roles[$key]))
            unset($this->role_names[$this->roles[$key]->getName()]);

        unset($this->roles[$key]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->roles);
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->role_names);
    }

    public function __get($name)
    {
        return $this->roles[$this->role_names[$name]];
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        throw new \Exception('disable this way');
    }

    public function __unset($name)
    {
        if ($this->isset($this->role_names[$name]))
            unset($this->roles[$this->role_names[$name]]);

        unset($this->role_names[$name]);
    }
}
