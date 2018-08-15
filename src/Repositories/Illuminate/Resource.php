<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Contracts\Support\Arrayable;
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

    public function getRole($key) : ?ResourceInterface
    {
        return $this->newQuery()->find($key);
    }

    /**
     * @param iterable $keys
     * @return ResourceCollection
     */
    public function getManyRoles(iterable $keys) : ResourceCollectionInterface
    {
        $result = $this->newQuery()->whereIn($this->getKeyName(), $keys)->get();

        if (! $result instanceof RoleCollectionInterface)
            $result = new ResourceCollection($result);

        return $result;
    }

    public function getRoleByName($name) : ?ResourceInterface
    {
        return $this->newQuery()->where('name', $name)->first();
    }

    public function getManyRolesByNames(iterable $names) : ResourceCollectionInterface
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

    public function addManyRoles(iterable $names)
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

    public function deleteManyRoles(iterable $ids)
    {
        return $this->newQuery()->whereIn($this->getKeyName(), $ids)->delete();
    }

    public function deleteRoleByName(string $name)
    {
        return $this->newQuery()->where('name', $name)->delete();
    }

    public function deleteManyRolesByNames(iterable $names)
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
     * @param iterable $permissions
     * @return bool
     */
    public function attach(iterable $permissions)
    {
        $permissions = $this->iterable2Array($permissions);

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
     * @param iterable $permissions
     * @return bool
     */
    public function detach(iterable $permissions)
    {
        $permissions = $this->iterable2Array($permissions);

        $permissions = array_intersect($this->getPermits(), $permissions);

        $this->permits()->whereIn('permit_key', $permissions)->delete();

        return true;
    }

    /**
     * @param iterable $permissions
     * @return bool
     */
    public function sync(iterable $permissions)
    {
        $permissions = $this->iterable2Array($permissions);

        $attach = array_diff($permissions, $this->getPermits());
        $detach = array_diff($this->getPermits(), $permissions);

        $this->attach($attach);
        $this->detach($detach);

        return true;
    }

    /**
     * @param iterable $permissions
     * @return bool
     */
    public function toggle(iterable $permissions)
    {
        $permissions = $this->iterable2Array($permissions);

        $attach = array_diff($permissions, $this->getPermits());
        $detach = array_intersect($this->getPermits(), $permissions);

        $this->attach($attach);
        $this->detach($detach);

        return true;
    }

    /**
     * @param iterable $items
     * @return array
     */
    protected function iterable2Array(iterable $items)
    {
        if (is_array($items))
            return $items;

        if ($items instanceof Arrayable)
            return $items->toArray();

        $array = [];

        foreach ($items as $key => $item)
        {
            $array[$key] = $item;
        }

        return $array;
    }
}
