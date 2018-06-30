<?php

namespace Taxusorg\Permission;

use ArrayAccess;
use Illuminate\Support\Str;
use Taxusorg\Permission\Exceptions\AccessDeniedException;
use Taxusorg\Permission\Exceptions\FrameworkError;
use Taxusorg\Permission\Traits\ManagerAble;

abstract class Permission implements ArrayAccess, PermissionInterface
{
    use ManagerAble;

    /**
     * @var string
     */
    static protected $key;

    /**
     * @return string
     */
    static public function key()
    {
        if (static::$key) return static::$key;

        return get_called_class();
    }

    /*static public function name()
    {
        if (static::) return static::$key;

        $array = explode('\\', get_called_class());

        return array_pop($array);
    }*/

    /**
     * @return string
     */
    public function getKeyAttribute()
    {
        return static::key();
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return [$this->getKeyAttribute()];
    }

    /**
     * @param RoleInterface|null $roles
     * @param bool $throw
     * @return bool
     * @throws FrameworkError
     * @throws AccessDeniedException
     */
    public function allows(RoleInterface $roles = null, $throw = false)
    {
        $before = static::getManager()->getBefore();

        foreach ($before as $item) {
            if (true === call_user_func($item, $this, static::getManager()))
                return true;
        }

        if (! $roles) $roles = static::getDefaultRoles();

        if (in_array($this->getKeyAttribute(), $roles->permitKeys()->all())) {
            return true;
        }

        if ($throw)
            throw new AccessDeniedException('Permission denied.');

        return false;
    }

    /**
     * @param RoleInterface|null $roles
     * @param bool $throw
     * @return true
     * @throws AccessDeniedException
     * @throws FrameworkError
     */
    static public function check(RoleInterface $roles = null, $throw = true)
    {
        $instance = static::getInstance();

        if (! $instance->allows($roles)) {
            if ($throw)
                throw new AccessDeniedException('Permission denied.');

            return false;
        }

        return true;
    }

    /**
     * @return RoleInterface
     * @throws FrameworkError
     */
    static public function getDefaultRoles()
    {
        return static::getManager()->getDefaultRoles();
    }

    /**
     * @return $this
     * @throws FrameworkError
     */
    static public function getInstance()
    {
        return static::getManager()->whereKeys(static::key())->first();
    }

    public function getAttribute($key)
    {
        $method = 'get'.Str::studly($key).'Attribute';
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return null;
    }

    public function setAttribute($key, $value)
    {
        // todo: set attribute.
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        //unset($this->attributes[$offset]);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param $name
     * @param $arguments
     * @throws FrameworkError
     */
    static public function __callStatic($name, $arguments)
    {
        static::getInstance()->$name(...$arguments);
    }
}
