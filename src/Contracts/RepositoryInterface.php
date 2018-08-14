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
     * @param iterable $keys
     * @return ResourceCollectionInterface
     */
    public function getManyRoles(iterable $keys);

    /**
     * @param $name
     * @return ResourceInterface|null
     */
    public function getRoleByName($name);

    /**
     * @param iterable $names
     * @return ResourceCollectionInterface
     */
    public function getManyRolesByNames(iterable $names);

    /**
     * @param $name
     * @return mixed $key
     */
    public function addRole($name);

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
    public function deleteRoleByName($name);

    /**
     * @param iterable $names
     * @return mixed
     */
    public function deleteManyRolesByNames(iterable $names);
}
