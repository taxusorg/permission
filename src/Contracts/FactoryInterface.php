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
     * @param array $array
     * @return $this
     */
    public function registerMany(array $array);

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
    public function getRole($key);

    /**
     * @param array $keys
     * @return RoleCollectionInterface
     */
    public function getManyRoles(array $keys) : RoleCollectionInterface;

    /**
     * @param string $name
     * @return RoleInterface|null
     */
    public function getRoleByName(string $name);

    /**
     * @param array $names
     * @return RoleCollectionInterface
     */
    public function getManyRolesByNames(array $names) : RoleCollectionInterface;

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
     * @param array $names
     * @return boolean|void
     */
    public function addManyRoles(array $names);

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
     * @param array $ids
     * @return mixed
     */
    public function deleteManyRoles(array $ids);

    /**
     * @param string $name
     * @return mixed
     */
    public function deleteRoleByName(string $name);

    /**
     * @param array $names
     * @return mixed
     */
    public function deleteManyRolesByNames(array $names);

    /**
     * @return Closure
     */
    public function getBeforeChecking();
}
