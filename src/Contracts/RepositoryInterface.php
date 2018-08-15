<?php

namespace Taxusorg\Permission\Contracts;

interface RepositoryInterface
{
    /**
     * @param $key
     * @return ResourceInterface|null
     */
    public function getRole($key) : ?ResourceInterface;

    /**
     * @param iterable $keys
     * @return ResourceCollectionInterface
     */
    public function getManyRoles(iterable $keys) : ResourceCollectionInterface;

    /**
     * @param string $name
     * @return ResourceInterface|null
     */
    public function getRoleByName(string $name) : ?ResourceInterface;

    /**
     * @param iterable $names
     * @return ResourceCollectionInterface
     */
    public function getManyRolesByNames(iterable $names) : ResourceCollectionInterface;

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
}
