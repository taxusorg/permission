<?php

namespace Taxusorg\Permission\Contracts;

interface ResourceSyncInterface
{
    /**
     * @param array $permissions
     * @return boolean
     */
    public function attach(array $permissions);

    /**
     * @param array $permissions
     * @return boolean
     */
    public function detach(array $permissions);

    /**
     * @param array $permissions
     * @return boolean
     */
    public function sync(array $permissions);

    /**
     * @param array $permissions
     * @return boolean
     */
    public function toggle(array $permissions);
}
