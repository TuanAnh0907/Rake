<?php

namespace TuanAnh0907\Rake;

use Illuminate\Support\Facades\Facade;

class RakeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rake';
    }
}