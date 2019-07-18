<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Qiniu extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'App\Services\Qiniu';
  }
}
