<?php

namespace App\Models;

use Hash;
use Series;
use Carbon\Carbon;
use App\Override\Model;

class AccessRecord extends Model
{
  public $table = 'access_records';

  const UPDATED_AT = null;

  public $timestamps = true;

  public $primaryKey = null;

  public $incrementing = false;
}
