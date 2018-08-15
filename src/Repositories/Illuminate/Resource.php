<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Taxusorg\Permission\Contracts\ResourceInterface;
use Taxusorg\Permission\Contracts\RepositoryInterface;
use Taxusorg\Permission\Contracts\RoleCollectionInterface;
use Taxusorg\Permission\Exceptions\TypeError;

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
     * @param iterable $keys
     * @return ResourceCollection
     * @throws TypeError
     */
    public function getManyRoles($keys) : ResourceCollection
    {
        if (! is_array($keys) && ! $keys instanceof \Traversable)
            throw new TypeError("Keys mast be array or instanceof Traversable.");

        $result = $this->newQuery()->whereIn($this->getKeyName(), $keys)->get();

        if (! $result instanceof RoleCollectionInterface)
            $result = new ResourceCollection($result);

        return $result;
    }

    public function getRoleByName($name)
    {
        return $this->newQuery()->where('name', $name)->first();
    }

    /**
     * @param iterable $names
     * @return ResourceCollection
     * @throws TypeError
     */
    public function getManyRolesByNames($names) : ResourceCollection
    {
        if (! is_array($names) && ! $names instanceof \Traversable)
            throw new TypeError("Names mast be array or instanceof Traversable.");

        $result = $this->newQuery()->whereIn('name', $names)->get();

        if (! $result instanceof RoleCollectionInterface)
            $result = new ResourceCollection($result);

        return $result;
    }

    public function addRole($name)
    {
        return $this->newQuery()->firstOrCreate(['name' => $name])->getKey();
    }

    /**
     * @param iterable $names
     * @return bool|void
     * @throws TypeError
     */
    public function addManyRoles($names)
    {
        if (! is_array($names) && ! $names instanceof \Traversable)
            throw new TypeError("Names mast be array or instanceof Traversable.");

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
     * @param iterable $ids
     * @return mixed
     * @throws TypeError
     */
    public function deleteManyRoles($ids)
    {
        if (! is_array($ids) && ! $ids instanceof \Traversable)
            throw new TypeError("Keys mast be array or instanceof Traversable.");

        return $this->newQuery()->whereIn($this->getKeyName(), $ids)->delete();
    }

    public function deleteRoleByName($name)
    {
        return $this->newQuery()->where('name', $name)->delete();
    }

    /**
     * @param iterable $names
     * @return mixed
     * @throws TypeError
     */
    public function deleteManyRolesByNames($names)
    {
        if (! is_array($names) && ! $names instanceof \Traversable)
            throw new TypeError("Names mast be array or instanceof Traversable.");

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
     * @throws TypeError
     */
    public function attach($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

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
     * @throws TypeError
     */
    public function detach($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

        $permissions = $this->iterable2Array($permissions);

        $permissions = array_intersect($this->getPermits(), $permissions);

        $this->permits()->whereIn('permit_key', $permissions)->delete();

        return true;
    }

    /**
     * @param iterable $permissions
     * @return bool
     * @throws TypeError
     */
    public function sync($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

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
     * @throws TypeError
     */
    public function toggle($permissions)
    {
        if (! is_array($permissions) && ! $permissions instanceof \Traversable)
            throw new TypeError("Permissions mast be array or instanceof Traversable.");

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
    protected function iterable2Array($items)
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
