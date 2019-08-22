<?php

namespace App\Models;

use Hash;
use Series;
use Carbon\Carbon;
use App\Override\Model;

class AccessRecord extends Model
{
  public $table = 'access_records';

  public $timestamps = true;
  const UPDATED_AT = null;
}
