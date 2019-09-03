<?php

namespace App\Models;

use Hash;
use Series;
use Carbon\Carbon;

class AccessRecord extends _Model
{
  public $table = 'access_records';

  public $timestamps = true;
  const UPDATED_AT = null;
}
