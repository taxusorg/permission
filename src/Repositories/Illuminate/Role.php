<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Database\Eloquent\Model;
use Taxusorg\Permission\Contracts\RepositoryInterface;
use Taxusorg\Permission\Contracts\ResourceInterface;

class Role extends Model implements ResourceInterface, RepositoryInterface
{
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permit::class, 'role_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissions()
    {
        return $this->getRelationValue('permissions');
    }

    public function find($key)
    {
        return $this->newQuery()->find($key);
    }

    /**
     * @param array $keys
     * @return \Taxusorg\Permission\Contracts\ResourceCollectionInterface| \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function finds(array $keys)
    {
        return $this->newQuery()->whereIn($this->getKeyName(), $keys)->get();
    }

    public function getByName($name)
    {
        return $this->newQuery()->where('name', $name)->first();
    }

    /**
     * @param array $models
     * @return RoleCollection
     */
    public function newCollection(array $models = [])
    {
        return new RoleCollection($models);
    }

    public function key()
    {
        return $this->getKey();
    }

    public function name()
    {
        return $this->getAttribute('name');
    }

    public function permits()
    {
        return $this->getPermissions()->pluck('permit_key')->toArray();
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function attach(array $permissions)
    {
        $permissions = array_diff($permissions, $this->permits());

        if (! $permissions) return true;

        $params = [];

        foreach ($permissions as $item) {
            $params[] = ['permit_key' => $item];
        }

        $this->permissions()->createMany($params);

        return true;
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function detach(array $permissions)
    {
        $permissions = array_intersect($this->permits(), $permissions);

        $this->permissions()->whereIn('permit_key', $permissions)->delete();

        return true;
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function sync(array $permissions)
    {
        $attach = array_diff($permissions, $this->permits());
        $detach = array_diff($this->permits(), $permissions);

        $this->attach($attach);
        $this->detach($detach);

        return true;
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function toggle(array $permissions)
    {
        $attach = array_diff($permissions, $this->permits());
        $detach = array_intersect($this->permits(), $permissions);

        $this->attach($attach);
        $this->detach($detach);

        return true;
    }
}