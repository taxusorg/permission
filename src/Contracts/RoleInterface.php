<?php

namespace Taxusorg\Permission\Contracts;

interface RoleInterface extends RoleVerifiableInterface
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
}
