<?php

namespace App\Facades;

class Qiniu extends _Facade
{
  protected static function getFacadeAccessor()
  {
    return 'App\Services\Qiniu';
  }
}
