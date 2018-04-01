<?php

namespace Taxusorg\Permission;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/permission.php' => config_path('permission.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        /*$this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');*/

        $this->app->extend(Manager::class, function (Manager $manager, $app) {
            $auth = Auth::guard($this->getConfigGuard());

            if ($auth->check())
                $manager->setDefaultUser($auth->user());

            $manager->addBefore(function ($permission) use ($auth) {
                if (in_array($auth->user()->id, $this->getConfigRootUserId()))
                    return true;
                if ($auth->user()->getRoles()
                    ->pluck('id')->intersect($this->getConfigRootRoleId())->isNotEmpty())
                    return true;

                return false;
            });

            return $manager;
        });
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/permission.php', 'permission'
        );

        $this->app->alias(Manager::class, 'permission');

        $this->app->singleton(Manager::class, function ($app) {
            $register = $this->getConfigPermissions();

            $manager = new Manager($register);

            return $manager;
        });

        Role::setManagerCallback(function () {
            return $this->app->make(Manager::class);
        });

        Permission::setManagerCallback(function () {
            return $this->app->make(Manager::class);
        });
    }

    /**
     * @return array
     */
    protected function getConfigPermissions()
    {
        return $this->app['config']['permission.permissions'];
    }

    /**
     * @return string|null|false
     */
    protected function getConfigGuard()
    {
        return $this->app['config']['permission.guard'];
    }

    protected function getConfigRootUserId()
    {
        return $this->app['config']['permission.root_user.id'] ?: [];
    }

    protected function getConfigRootRoleId()
    {
        return $this->app['config']['permission.root_role.id'] ?: [];
    }
}
