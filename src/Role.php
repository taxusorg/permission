<?php

namespace Taxusorg\Permission;

use Taxusorg\Permission\Contracts\RoleInterface;
use Taxusorg\Permission\Contracts\RoleResourceInterface;

class Role implements RoleInterface
{
    protected $resource;

    public function __construct(RoleResourceInterface  $resource)
    {
        $this->resource = $resource;
    }

    public function key()
    {
        return $this->resource->key();
    }

    public function name()
    {
        return $this->resource->name();
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function attach(...$permissions)
    {
        $permissions = $this->paramFormat($permissions);

        return $this->resource->attach($permissions);
    }

    /**
     * @param string|array ...$permissions string
     * @return bool
     */
    public function detach(...$permissions)
    {
        $permissions = $this->paramFormat($permissions);

        return $this->resource->detach($permissions);
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function sync(...$permissions)
    {
        $permissions = $this->paramFormat($permissions);

        return $this->resource->sync($permissions);
    }

    /**
     * @param string|array ...$permissions
     * @return bool
     */
    public function toggle(...$permissions)
    {
        $permissions = $this->paramFormat($permissions);

        return $this->resource->toggle($permissions);
    }

    public function check($permission)
    {
        // TODO: Implement check() method.
    }

    public function can($permission)
    {
        // TODO: Implement can() method.
    }

    protected function paramFormat($params, $data = [])
    {
        foreach ($params as $param) {
            if (is_string($param)) {
                $data[] = $param;
            } elseif (is_array($param)) {
                $data = $this->paramFormat($param, $data);
            }
        }

        return $data;
    }
}
