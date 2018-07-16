<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Taxusorg\Permission\Factory;
use Taxusorg\Permission\Repositories\Illuminate\Permit;
use Taxusorg\Permission\Repositories\Illuminate\Role as RoleRepository;
use Taxusorg\Permission\Role;

class SimpleTest extends TestCase
{
    public function testSimple()
    {
        $repository = new RoleRepository();
        $factory = new Factory($repository);

        $roles = $factory->getRoles([1, 2]);

        $f = $roles->can('test1');
        $t = $roles->can('test4');

        $this->assertFalse($f);
        $this->assertTrue($t);
        $this->assertTrue(true);
    }
}
