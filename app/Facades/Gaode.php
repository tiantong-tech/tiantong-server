<?php

namespace App\Facades;

class Gaode extends _Facade
{
  protected static function getFacadeAccessor()
  {
    return 'App\Services\Gaode';
  }
}
