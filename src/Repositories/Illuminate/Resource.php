<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Database\Eloquent\Model;
use Taxusorg\Permission\Contracts\ResourceCollectionInterface;
use Taxusorg\Permission\Contracts\ResourceInterface;
use Taxusorg\Permission\Contracts\RepositoryInterface;
use Taxusorg\Permission\Contracts\RoleCollectionInterface;

class Resource extends Model implements ResourceInterface, RepositoryInterface
{
    protected $table = 'roles';

    protected $fillable = ['name'];

    public $timestamps = false;

    public function permits()
    {
        return $this->hasMany(Permit::class, 'role_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermitsModels()
    {
        return $this->getRelationValue('permits');
    }

    public function getRole($key)
    {
        return $this->newQuery()->find($key);
    }

    /**
     * @param array $keys
     * @return ResourceCollection
     */
    public function getManyRoles(array $keys) : ResourceCollectionInterface
    {
        $result = $this->newQuery()->whereIn($this->getKeyName(), $keys)->get();

        if (! $result instanceof RoleCollectionInterface)
            $result = new ResourceCollection($result);

        return $result;
    }

    public function getRoleByName(string $name)
    {
        return $this->newQuery()->where('name', $name)->first();
    }

    /**
     * @param array $names
     * @return ResourceCollection
     */
    public function getManyRolesByNames(array $names) : ResourceCollectionInterface
    {
        $result = $this->newQuery()->whereIn('name', $names)->get();

        if (! $result instanceof RoleCollectionInterface)
            $result = new ResourceCollection($result);

        return $result;
    }

    public function addRole(string $name)
    {
        return $this->newQuery()->firstOrCreate(['name' => $name])->getKey();
    }

    /**
     * @param array $names
     * @return bool|void
     */
    public function addManyRoles(array $names)
    {
        foreach ($names as $name) {
            $this->newQuery()->firstOrCreate(['name' => $name]);
        }
    }

    public function renameRole(string $old, string $new)
    {
        return $this->newQuery()->where('name', $old)->update(['name' => $new]);
    }

    public function deleteRole($id)
    {
        return $this->newQuery()->where($this->getKeyName(), $id)->delete();
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteManyRoles(array $ids)
    {
        return $this->newQuery()->whereIn($this->getKeyName(), $ids)->delete();
    }

    public function deleteRoleByName(string $name)
    {
        return $this->newQuery()->where('name', $name)->delete();
    }

    /**
     * @param array $names
     * @return mixed
     */
    public function deleteManyRolesByNames(array $names)
    {
        return $this->newQuery()->whereIn('name', $names)->delete();
    }

    /**
     * @param array $models
     * @return ResourceCollection
     */
    public function newCollection(array $models = []) : ResourceCollection
    {
        return new ResourceCollection($models);
    }

    public function getName() : string
    {
        return $this->getAttribute('name');
    }

    public function getPermits() : array
    {
        return $this->getPermitsModels()->pluck('permit_key')->toArray();
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function attach(array $permissions)
    {
        $permissions = array_diff($permissions, $this->getPermits());

        if (! $permissions) return true;

        $params = [];

        foreach ($permissions as $item) {
            $params[] = ['permit_key' => $item];
        }

        $this->permits()->createMany($params);

        return true;
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function detach(array $permissions)
    {
        $permissions = array_intersect($this->getPermits(), $permissions);

        $this->permits()->whereIn('permit_key', $permissions)->delete();

        return true;
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function sync(array $permissions)
    {
        $attach = array_diff($permissions, $this->getPermits());
        $detach = array_diff($this->getPermits(), $permissions);

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
        $attach = array_diff($permissions, $this->getPermits());
        $detach = array_intersect($this->getPermits(), $permissions);

        $this->attach($attach);
        $this->detach($detach);

        return true;
    }
}
