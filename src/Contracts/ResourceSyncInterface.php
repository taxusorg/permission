<?php

namespace Taxusorg\Permission\Contracts;

interface ResourceSyncInterface
{
    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function attach(iterable $permissions);

    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function detach(iterable $permissions);

    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function sync(iterable $permissions);

    /**
     * @param iterable $permissions
     * @return boolean
     */
    public function toggle(iterable $permissions);
}
