<?php

namespace Tests;

use Dotenv\Dotenv;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;
use PHPUnit\Framework\TestCase;
use Taxusorg\Permission\Manager;
use Taxusorg\Permission\Permission;
use Taxusorg\Permission\RolePermit;
use Taxusorg\Permission\Role;
use Taxusorg\Permission\RoleCollection;
use Tests\Permissions\Test1;
use Tests\Permissions\Test2;

class SimpleTest extends TestCase
{
    public function __construct(...$p)
    {
        parent::__construct(...$p);

        date_default_timezone_set('PRC');
        $dotenv = new Dotenv('./');
        $dotenv->load();

        $connection = new ConnectionResolver([
            'mysql' => new MySqlConnection(
                new \PDO(
                    "mysql:host=" . env('DB_HOST', '127.0.0.1') .
                    ";port=" . env('DB_PORT', '3306') .
                    ";dbname=" . env('DB_DATABASE', 'forge'),
                    env('DB_USERNAME', 'forge'),
                    env('DB_PASSWORD', '')
                )
            ),
        ]);
        $connection->setDefaultConnection('mysql');
        Model::setConnectionResolver($connection);
    }

    public function testSimple()
    {
        $role = Role::first();

        $manager = new Manager();
        $manager->setDefaultRoles(new RoleCollection([$role]));

        //Role::setManager($manager);
        Role::setManagerCallback(function () use ($manager) {
            return $manager;
        });
        Permission::setManager($manager);

        $manager->register(Test1::class);
        $manager->register(Test2::class);

        $all = $manager->all();
        $role->syncPermits(Test2::key());
        var_dump($role->permits);
        //print_r(Permission::all());

        $this->assertTrue(true);
    }
}
