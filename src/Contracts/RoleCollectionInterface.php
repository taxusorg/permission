<?php

namespace Taxusorg\Permission\Contracts;

interface RoleCollectionInterface extends RoleVerifiableInterface, \IteratorAggregate
{
    /**
     * @param RoleInterface $role
     * @return void
     */
    public function push(RoleInterface $role);

    /**
     * @return RoleInterface
     */
    public function pop();

    /**
     * @param RoleInterface $role
     * @param $key
     * @return mixed
     */
    public function prepend(RoleInterface $role, $key = null);

    /**
     * @param $key
     * @return RoleInterface
     */
    public function pull($key = null);
}
