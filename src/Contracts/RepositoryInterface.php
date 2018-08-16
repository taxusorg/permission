<?php

namespace Taxusorg\Permission\Contracts;

interface RepositoryInterface
{
    /**
     * @param $key
     * @return ResourceInterface|null
     */
    public function getRole($key);

    /**
     * @param array $keys
     * @return ResourceCollectionInterface
     */
    public function getManyRoles(array $keys) : ResourceCollectionInterface;

    /**
     * @param string $name
     * @return ResourceInterface|null
     */
    public function getRoleByName(string $name);

    /**
     * @param array $names
     * @return ResourceCollectionInterface
     */
    public function getManyRolesByNames(array $names) : ResourceCollectionInterface;

    /**
     * @param string $permit
     * @return ResourceCollectionInterface
     */
    public function getManyRolesByPermit(string $permit) : ResourceCollectionInterface;

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
}
