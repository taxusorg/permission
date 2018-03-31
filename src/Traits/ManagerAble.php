<?php

namespace Taxusorg\Permission\Traits;

use Closure;
use Taxusorg\Permission\Manager;
use Taxusorg\Permission\Exceptions\FrameworkError;

trait ManagerAble
{

    /**
     * @var Manager
     */
    static protected $manager;

    /**
     * @var Closure
     */
    static protected $manager_callback;

    /**
     * @param Manager $manager
     */
    static public function setManager(Manager $manager)
    {
        static::$manager = $manager;
    }

    /**
     * @param Closure $closure
     */
    static public function setManagerCallback(Closure $closure)
    {
        static::$manager_callback = $closure;
    }

    /**
     * @return Manager
     * @throws FrameworkError
     */
    static public function getManager()
    {
        if (! static::$manager) {
            if (static::$manager_callback) {
                $manager = call_user_func(static::$manager_callback);
                if ($manager instanceof Manager)
                    static::$manager = $manager;
                else
                    throw new FrameworkError('Closure $manager_callback mast return ' . Manager::class);
            } else {
                throw new FrameworkError('Manager not found. ' . static::class . '::setManager.');
            }
        }

        return static::$manager;
    }
}
