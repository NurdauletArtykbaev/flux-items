<?php

namespace Nurdaulet\FluxItems\Facades;

use Illuminate\Support\Facades\Facade;

class ItemsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'items';
    }
}
