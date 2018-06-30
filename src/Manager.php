<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Exceptions\FrameworkError;
use Taxusorg\Permission\PermissionCollection as Collection;

class Manager
{
    /**
     * @var PermissionCollection
     */
    protected $repository;

    /**
     * @var RoleInterface
     */
    protected $default_roles;

    /**
     * @var UserInterface
     */
    protected $default_user;

    protected $default_user_closure;

    /**
     * @var array
     */
    protected $before = [];

    /**
     * Manager constructor.
     * @param null|string|Permission|array $permission
     * @throws \Error
     */
    public function __construct($permission = null)
    {
        $this->repository = new Collection();

        $this->register($permission);
    }

    /**
     * @param string|Permission|array $permission
     * @throws \Error
     */
    public function register($permission)
    {
        if (is_array($permission)) {
            foreach ($permission as $item) {
                $this->doRegister($item);
            }
        } else {
            $this->doRegister($permission);
        }
    }

    /**
     * @param $permission
     * @throws FrameworkError
     */
    protected function doRegister($permission)
    {
        if (! $permission) return;

        $permission = is_string($permission) ? new $permission : $permission;

        if (! $permission instanceof Permission) {
            throw new FrameworkError('permission instanceof '.Permission::class);
        }

        $this->repository->push($permission);
    }

    /**
     * @return PermissionCollection
     */
    public function all()
    {
        return $this->repository;
    }

    /**
     * @param $keys
     * @return PermissionCollection
     */
    public function whereKeys($keys)
    {
        if (is_string($keys)) {
            $keys = [$keys];
        } elseif ($keys instanceof PermissionInterface) {
            $keys = $keys->getKeys();
        } elseif (is_array($keys) || $keys instanceof \ArrayAccess) {
            $keys = (new Collection($keys))->map(function($item){
                if (is_string($item)) {
                    return $item;
                } elseif ($item instanceof PermissionInterface) {
                    return $item->getKeys();
                }
                return false;
            })->flatten(1)->all();
        }

        return $this->repository->whereIn('key',$keys);
    }

    public function setDefaultUser(UserInterface $user)
    {
        $this->default_user = $user;
    }

    public function setDefaultUserClosure(\Closure $closure)
    {
        $this->default_user_closure = $closure;
    }

    /**
     * @return UserInterface
     */
    public function getDefaultUser()
    {
        if (! $this->default_user && $this->default_user_closure)
            $this->setDefaultUser(call_user_func($this->default_user_closure));
        return $this->default_user;
    }

    /**
     * @param RoleInterface|null $roles
     */
    public function setDefaultRoles(RoleInterface $roles = null)
    {
        $this->default_roles = $roles;
    }

    /**
     * @return RoleInterface
     */
    public function getDefaultRoles()
    {
        if (! $this->default_roles && $this->default_user) {
            $this->setDefaultRoles($this->default_user->getRoles());
        }

        return $this->default_roles ?: new RoleCollection();
    }

    /**
     * @param \Closure $closure
     */
    public function addBefore(\Closure $closure)
    {
        $this->before[] = $closure;
    }

    /**
     * @return array
     */
    public function getBefore()
    {
        return $this->before;
    }
}
