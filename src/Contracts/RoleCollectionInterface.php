<?php

namespace Taxusorg\Permission\Contracts;

use ArrayAccess;
use IteratorAggregate;

interface RoleCollectionInterface extends VerifiableInterface, RoleSyncInterface, ArrayAccess, IteratorAggregate
{
    /**
     * @param RoleInterface $role
     * @return void
     */
    public function push(RoleInterface $role);

    /**
     * @return RoleInterface
     */
    public function pop() : RoleInterface;

    /**
     * @param mixed $offset
     * @return RoleInterface
     */
    public function offsetGet($offset);

    /**
     * @param $name
     * @return boolean
     */
    public function __isset($name);

    /**
     * @param $name
     * @return RoleInterface
     */
    public function __get($name);

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value);

    /**
     * @param $name
     * @return void
     */
    public function __unset($name);
}
