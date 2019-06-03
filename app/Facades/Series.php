<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Series extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\Series';
    }
}
