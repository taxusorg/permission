<?php

namespace Taxusorg\Permission\Contracts;

interface CollectionInterface
{
    /**
     * @return mixed
     */
    public function pop();

    /**
     * @param $key
     * @return mixed
     */
    public function pull($key);

    /**
     * @param $value
     * @return mixed
     */
    public function push($value);

    /**
     * @param $value
     * @param $key
     * @return mixed
     */
    public function prepend($value, $key);
}
