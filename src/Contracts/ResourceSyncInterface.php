<?php

namespace Taxusorg\Permission\Contracts;

interface ResourceSyncInterface
{
    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function attach($permissions);

    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function detach($permissions);

    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function sync($permissions);

    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function toggle($permissions);
}
