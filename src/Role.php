<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Contracts\FactoryInterface;
use Taxusorg\Permission\Contracts\ResourceInterface;
use Taxusorg\Permission\Contracts\RoleInterface;
use Taxusorg\Permission\Exceptions\AccessDeniedException;

class Role implements RoleInterface
{
    protected $resource;

    protected $factory;

    public function __construct(ResourceInterface $resource, FactoryInterface $factory)
    {
        $this->resource = $resource;

        $this->factory = $factory;
    }

    public function getKey()
    {
        return $this->resource->getKey();
    }

    public function getName() : string
    {
        return $this->resource->getName();
    }

    public function getPermits() : array
    {
        return array_intersect($this->resource->getPermits(), $this->factory->getPermissions());
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function attach(...$permissions)
    {
        $permissions = array_intersect($this->paramFormat($permissions), $this->factory->getPermissions());

        $this->resource->attach($permissions);

        $this->flushResource();

        return true;
    }

    /**
     * @param string|array ...$permissions string
     * @return bool
     */
    public function detach(...$permissions)
    {
        $permissions = $this->paramFormat($permissions);

        $this->resource->detach($permissions);

        $this->flushResource();

        return true;
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function sync(...$permissions)
    {
        $permissions = array_intersect($this->paramFormat($permissions), $this->factory->getPermissions());

        $this->resource->sync($permissions);

        $this->flushResource();

        return true;
    }

    /**
     * @param string|array ...$permissions
     * @return boolean
     */
    public function toggle(...$permissions)
    {
        $params = $this->paramFormat($permissions);

        $permissions = array_intersect($params, $this->factory->getPermissions());
        $deletes = array_diff($params, $this->factory->getPermissions());

        $this->resource->detach($deletes);
        $this->resource->toggle($permissions);

        $this->flushResource();

        return true;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function check($permission) : bool
    {
        if ($closure = $this->factory->getBeforeChecking())
        {
            $result = call_user_func($closure, $permission, $this, $this->factory);
            if ($result === true || $result === false) {
                return $result;
            }
        }

        $permits = $this->getPermits();

        return in_array($permission, $permits);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function can($permission) : bool
    {
        return $this->check($permission);
    }

    /**
     * @param $permission
     * @return bool|true
     * @throws AccessDeniedException
     */
    public function allowsOrFail($permission)
    {
        if (! $this->can($permission))
            throw new AccessDeniedException('Access Denied.');

        return true;
    }

    /**
     * @param $params
     * @param array|[] $data
     * @return array
     */
    protected function paramFormat(iterable $params, $data = [])
    {
        foreach ($params as $param) {
            if (is_string($param)) {
                $data[] = $param;
            } elseif (is_array($param) || $param instanceof \Traversable) {
                $data = $this->paramFormat($param, $data);
            }
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function flushResource()
    {
        $this->resource = $this->factory->getRepository()->getRole($this->getKey());
    }
}
