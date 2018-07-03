<?php

namespace Taxusorg\Permission\Contracts;

interface RoleInterface
{
    public function key();

    public function name();

    /**
     * @param string|array ...$permissions string
     * @return boolean
     */
    public function attach(...$permissions);

    /**
     * @param string|array ...$permissions string
     * @return boolean
     */
    public function detach(...$permissions);

    /**
     * @param string|array ...$permissions string
     * @return boolean
     */
    public function sync(...$permissions);

    /**
     * @param string|array ...$permissions string
     * @return boolean
     */
    public function toggle(...$permissions);

    public function check($permission);

    public function can($permission);
}
