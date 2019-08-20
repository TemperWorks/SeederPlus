<?php

namespace Temper\SeederPlus;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Temper\SeederPlus\Skeleton\SkeletonClass
 */
class SeederPlusFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'seederplus';
    }
}
