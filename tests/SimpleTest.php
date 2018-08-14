<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Taxusorg\Permission\Factory;
use Taxusorg\Permission\Repositories\Illuminate\Resource as RoleRepository;

class SimpleTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testSimple()
    {
        $ps = ['test1', 'test2', 'test3', 'test4', 'test5'];

        $repository = new RoleRepository();
        $factory = new Factory($repository);

        $factory->registerMany($ps);

        $factory->addManyRoles(['roleA', 'roleB']);

        $roles = $factory->getManyRolesByNames(['roleA', 'roleB']);
        $r = $factory->getRoleByName('ro');
        $rs = $factory->getManyRolesByNames(['r1', 'r2']);

        $roles->sync('test1', 'test2');
        $roles->roleA->attach('test3', 'test6');

        $this->assertTrue($roles->roleA->check('test1'));
        $this->assertTrue($roles->roleA->check('test2'));
        $this->assertTrue($roles->roleA->check('test3'));
        $this->assertFalse($roles->roleA->check('test4'));
        $this->assertFalse($roles->roleA->check('test5'));
        $this->assertFalse($roles->roleA->check('test6'));
        $this->assertTrue($roles->roleB->check('test1'));
        $this->assertTrue($roles->roleB->check('test2'));
        $this->assertFalse($roles->roleB->check('test3'));
        $this->assertFalse($roles->roleB->check('test4'));
        $this->assertFalse($roles->roleB->check('test6'));
    }
}
