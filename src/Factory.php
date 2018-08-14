<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Contracts\FactoryInterface;
use Taxusorg\Permission\Contracts\RepositoryInterface;
use Taxusorg\Permission\Contracts\ResourceCollectionInterface;
use Taxusorg\Permission\Contracts\ResourceInterface;
use Taxusorg\Permission\Contracts\UserInterface;
use Taxusorg\Permission\Exceptions\AccessDeniedException;
use Taxusorg\Permission\Exceptions\DefaultUserNotFound;
use Taxusorg\Permission\Exceptions\PermissionDuplication;
use Taxusorg\Permission\Exceptions\RoleDuplication;
use Taxusorg\Permission\Exceptions\TypeError;

class Factory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var \Closure|null
     */
    protected $userResolver;

    /**
     * @var \Closure|null
     */
    protected $beforeCheck;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @return array
     */
    public function getPermissions() : array
    {
        return $this->permissions;
    }

    /**
     * @param string $name
     * @throws PermissionDuplication
     */
    protected function pushPermission(string $name)
    {
        if ($this->isRegistered($name))
            throw new PermissionDuplication("$name already exists");

        array_push($this->permissions, $name);
    }

    /**
     * @param string $name
     * @return $this
     * @throws PermissionDuplication
     */
    public function register(string $name)
    {
        $this->pushPermission($name);

        return $this;
    }

    /**
     * @param iterable $array
     * @return $this
     * @throws PermissionDuplication
     */
    public function registerMany(iterable $array)
    {
        foreach ($array as $item) {
            $this->register($item);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isRegistered(string $name) : bool
    {
        return in_array($name, $this->permissions);
    }

    /**
     * @param integer|string $key
     * @return null|Role
     * @throws TypeError
     */
    public function getRole($key)
    {
        if (! is_integer($key) && ! is_string($key))
            throw new TypeError("Role's key mast be integer or string.");

        return $this->resolverRole($this->repository->getRole($key));
    }

    /**
     * @param iterable $keys
     * @return RoleCollection
     */
    public function getManyRoles(iterable $keys)
    {
        return $this->resolverRoleCollection($this->repository->getManyRoles($keys));
    }

    /**
     * @param string $name
     * @return null|Role
     */
    public function getRoleByName(string $name)
    {
        return $this->resolverRole($this->repository->getRoleByName($name));
    }

    /**
     * @param iterable $names
     * @return RoleCollection
     */
    public function getManyRolesByNames(iterable $names)
    {
        return $this->resolverRoleCollection($this->repository->getManyRolesByNames($names));
    }

    /**
     * @param ResourceInterface|null $resource
     * @return null|Role
     */
    protected function resolverRole(ResourceInterface $resource = null)
    {
        if (! $resource) return null;

        return new Role($resource, $this);
    }

    /**
     * @param ResourceCollectionInterface $rCollection
     * @return RoleCollection
     */
    protected function resolverRoleCollection(ResourceCollectionInterface $rCollection)
    {
        $collection = new RoleCollection();

        foreach ($rCollection as $item)
        {
            $collection->push($this->resolverRole($item));
        }

        return $collection;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws RoleDuplication
     */
    public function addRole(string $name)
    {
        if ($this->repository->getRoleByName($name))
            throw new RoleDuplication("Role $name already exists.");

        return $this->repository->addRole($name);
    }

    public function addManyRoles(iterable $names)
    {
        return $this->repository->addManyRoles($names);
    }

    /**
     * @param string $old
     * @param string $new
     * @return mixed
     * @throws RoleDuplication
     */
    public function renameRole(string $old, string $new)
    {
        if ($this->repository->getRoleByName($new))
            throw new RoleDuplication("Role $new already exists.");

        return $this->repository->renameRole($old, $new);
    }

    public function deleteRole($id)
    {
        return $this->repository->deleteRole($id);
    }

    public function deleteManyRoles(array $ids)
    {
        return $this->repository->deleteManyRoles($ids);
    }

    public function deleteRoleByName(string $name)
    {
        return $this->repository->deleteRoleByName($name);
    }

    public function deleteManyRolesByNames(iterable $names)
    {
        return $this->repository->deleteManyRolesByNames($names);
    }

    /**
     * @param string $name
     * @param UserInterface|null $user
     * @return bool
     * @throws DefaultUserNotFound
     */
    public function check(string $name, UserInterface $user = null) : bool
    {
        $user = $user ?: $this->defaultUser();
        if (! $user)
            return false;

        if (! $this->isRegistered($name))
            return false;

        if ($this->resolverRoleCollection($user->getRoles())->check($name))
            return true;

        return in_array($name, $user->getPermits());
    }

    /**
     * @param string $name
     * @param UserInterface|null $user
     * @return bool
     * @throws DefaultUserNotFound
     */
    public function can(string $name, UserInterface $user = null): bool
    {
        return $this->check($name, $user);
    }

    /**
     * @param string $name
     * @param UserInterface|null $user
     * @return bool
     * @throws DefaultUserNotFound
     */
    public function allows(string $name, UserInterface $user = null) : bool
    {
        return $this->check($name, $user);
    }

    /**
     * @param string $name
     * @param UserInterface|null $user
     * @return true
     * @throws AccessDeniedException
     * @throws DefaultUserNotFound
     */
    public function allowsOrFail(string $name, UserInterface $user = null)
    {
        if (! $this->check($name, $user))
            throw new AccessDeniedException();

        return true;
    }

    /**
     * @return UserInterface|null
     */
    public function user() : UserInterface
    {
        if (! $this->userResolver())
            return null;

        $user = call_user_func($this->userResolver());

        return $user;
    }

    /**
     * @return UserInterface
     * @throws DefaultUserNotFound
     */
    protected function defaultUser()
    {
        if (! $user = $this->user())
            throw new DefaultUserNotFound();

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return object
     */
    public function use(UserInterface $user)
    {
        $factory = $this;
        return new class($user, $factory) {
            /**
             * @var UserInterface
             */
            private $user;

            /**
             * @var FactoryInterface
             */
            private $factory;

            public function __construct($user, $factory)
            {
                $this->user = $user;
                $this->factory = $factory;
            }

            /**
             * @param $name
             * @return bool
             */
            public function check(string $name)
            {
                return $this->factory->check($name, $this->user);
            }

            /**
             * @param $name
             * @return bool
             */
            public function allows(string $name)
            {
                return $this->factory->allows($name, $this->user);
            }
        };
    }

    /**
     * Get the user resolver callback.
     *
     * @return \Closure
     */
    public function userResolver() : \Closure
    {
        return $this->userResolver;
    }

    /**
     * Set the callback to be used to resolve users.
     *
     * @param  \Closure  $userResolver
     * @return $this
     */
    public function resolveUsersUsing(\Closure $userResolver)
    {
        $this->userResolver = $userResolver;

        return $this;
    }

    /**
     * @param \Closure $before
     */
    public function beforeChecking(\Closure $before)
    {
        $this->beforeCheck = $before;
    }

    /**
     * @return \Closure|null
     */
    public function getBeforeChecking() : \Closure
    {
        return $this->beforeCheck;
    }
}
