<?php

namespace Tests\Permissions;

use Taxusorg\Permission\Permission;

class Test1 extends Permission
{
    static public function key()
    {
        return 'simple_permission';
    }
}
