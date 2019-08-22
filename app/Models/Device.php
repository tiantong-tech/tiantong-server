<?php

namespace App\Models;

use App\Override\Model;

class Device extends Model
{
  public $table = 'devices';

  public $timestamps = true;
  const UPDATED_AT = null;
}
