<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;

class ApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Api';
    }
}
