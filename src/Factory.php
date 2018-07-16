<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Contracts\FactoryInterface;
use Taxusorg\Permission\Contracts\RepositoryInterface;
use Taxusorg\Permission\Contracts\ResourceInterface;

class Factory implements FactoryInterface
{
    protected $repository;

    protected $roles = [];

    protected $roles_name = [];

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getRole($key)
    {
        if (isset($this->roles[$key]))
            return $this->roles[$key];

        if ($resource = $this->repository->find($key)) {
            return $this->resolveRole($resource);
        }

        return false;
    }

    /**
     * @param array $keys
     * @return RoleCollection
     */
    public function getRoles(array $keys)
    {
        $resources = $this->repository->finds($keys);

        $collection = new RoleCollection();

        foreach ($resources as $resource) {
            $role = $this->resolveRole($resource);
            $collection->push($role);
        }

        return $collection;
    }

    public function getRoleByName($name)
    {
        if (isset($this->roles_name[$name]))
            return $this->roles_name[$name];

        if ($resource = $this->repository->getByName($name)) {
            return $this->resolveRole($resource);
        }

        return false;
    }

    protected function resolveRole(ResourceInterface $resource)
    {
        $role = new Role($resource);
        
        $this->roles[$role->key()] = $role;
        $this->roles_name[$role->name()] = $role;

        return $role;
    }
}
