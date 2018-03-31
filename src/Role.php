<?php

namespace Taxusorg\Permission;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Taxusorg\Permission\Exceptions\FrameworkError;
use Taxusorg\Permission\RoleCollection as Collection;
use Taxusorg\Permission\Traits\ManagerAble;

class Role extends Model implements RoleInterface
{
    use ManagerAble;

    /**
     * @var string
     */
    protected $permit_key_name = 'permit_key';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return string
     */
    public function getPermitKeyName()
    {
        return $this->permit_key_name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolePermits()
    {
        return $this->hasMany(RolePermit::class);
    }

    /**
     * @return \Illuminate\Support\Collection|static
     */
    public function permitKeys()
    {
        return $this->rolePermits()->get()->pluck($this->getPermitKeyName())->unique();
    }

    /**
     * @return \Illuminate\Support\Collection|Role
     */
    public function getPermitKeysAttribute()
    {
        return $this->permitKeys();
    }

    /*public function permissions()
    {
        return ;
    }*/

    /**
     * @return PermissionCollection
     * @throws FrameworkError
     */
    public function getPermitsAttribute()
    {
        return static::getManager()->whereKeys($this->permitKeys());
    }

    /**
     * @param $permissions
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws FrameworkError
     */
    public function attachPermits($permissions)
    {
        $keys = static::getManager()->whereKeys($permissions)->keys();

        return $this->rolePermits()->createMany($keys->map(function($item){
            return [$this->getPermitKeyName() => $item];
        })->all());
    }

    /**
     * @param $permissions
     * @return mixed
     * @throws FrameworkError
     */
    public function detachPermits($permissions)
    {
        $keys = static::getManager()->whereKeys($permissions)->keys();

        return $this->rolePermits()->whereIn($this->getPermitKeyName(), $keys->all())->delete();
    }

    /**
     * @param $permissions
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws FrameworkError
     */
    public function syncPermits($permissions)
    {
        $keys = static::getManager()->whereKeys($permissions)->keys();

        $allows_keys = $this->rolePermits()->get()->pluck($this->getPermitKeyName());

        $delete_keys = $allows_keys->filter(function ($item) use ($keys) {
            return ! in_array($item, $keys->all());
        });
        $insert_keys = $keys->diff($allows_keys);

        $this->rolePermits()->whereIn($this->getPermitKeyName(), $delete_keys->all())->delete();
        return $this->rolePermits()->createMany($insert_keys->map(function($item){
            return [$this->getPermitKeyName() => $item];
        })->all());
    }

    /**
     * @param array $models
     * @return RoleCollection
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }

    /**
     * @param $permission
     * @return bool
     * @throws FrameworkError
     */
    public function allows($permission)
    {
        $permission = static::getManager()->whereKeys($permission);

        return $permission->allows($this);
    }

    /**
     * @return RoleCollection
     * @throws FrameworkError
     */
    static public function getDefaultRoles()
    {
        return static::getManager()->getDefaultRoles();
    }
}
