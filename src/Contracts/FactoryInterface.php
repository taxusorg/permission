<?php

namespace Taxusorg\Permission\Contracts;

use Closure;

interface FactoryInterface
{
    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;

    /**
     * @return array
     */
    public function getPermissions() : array;

    /**
     * @param string $name
     * @return $this
     */
    public function register(string $name);

    /**
     * @param iterable $array
     * @return $this
     */
    public function registerMany(iterable $array);

    /**
     * @param string $name
     * @return boolean
     */
    public function isRegistered(string $name) : bool;

    /**
     * @param $name
     * @param UserInterface|null $user
     * @return boolean
     */
    public function check(string $name, UserInterface $user = null) : bool;

    /**
     * @param $name
     * @param UserInterface|null $user
     * @return boolean
     */
    public function can(string $name, UserInterface $user = null) : bool;

    /**
     * @param $name
     * @param UserInterface|null $user
     * @return boolean
     */
    public function allows(string $name, UserInterface $user = null) : bool;

    /**
     * @param $name
     * @param UserInterface|null $user
     * @return true
     * @throws \Taxusorg\Permission\Exceptions\AccessDeniedException
     */
    public function allowsOrFail(string $name, UserInterface $user = null) : bool;

    /**
     * @param $key
     * @return RoleInterface|null
     */
    public function getRole($key) : ?RoleInterface;

    /**
     * @param iterable $keys
     * @return RoleCollectionInterface
     */
    public function getManyRoles(iterable $keys) : RoleCollectionInterface;

    /**
     * @param string $name
     * @return RoleInterface|null
     */
    public function getRoleByName(string $name) : ?RoleInterface;

    /**
     * @param iterable $names
     * @return RoleCollectionInterface
     */
    public function getManyRolesByNames(iterable $names) : RoleCollectionInterface;

    /**
     * @param string $permit
     * @return RoleCollectionInterface
     */
    public function getManyRolesByPermit(string $permit) : RoleCollectionInterface;

    /**
     * @param string $name
     * @return mixed $key
     */
    public function addRole(string $name);

    /**
     * @param iterable $names
     * @return boolean|void
     */
    public function addManyRoles(iterable $names);

    /**
     * @param string $old
     * @param string $new
     * @return mixed
     */
    public function renameRole(string $old, string $new);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRole($id);

    /**
     * @param iterable $ids
     * @return mixed
     */
    public function deleteManyRoles(iterable $ids);

    /**
     * @param string $name
     * @return mixed
     */
    public function deleteRoleByName(string $name);

    /**
     * @param iterable $names
     * @return mixed
     */
    public function deleteManyRolesByNames(iterable $names);

    /**
     * @return Closure
     */
    public function getBeforeChecking();
}
