<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Taxusorg\Permission\Repositories\Illuminate\Permit;
use Taxusorg\Permission\Repositories\Illuminate\Role as RoleRepository;
use Taxusorg\Permission\Role;

class SimpleTest extends TestCase
{
    public function testSimple()
    {
        $repository = new RoleRepository();
        $role = new Role($repository->getByName('test_role'));

        $role->toggle(['test3', 'test4']);

        $this->assertTrue(true);
    }
}
