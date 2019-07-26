<?php

namespace App\Models;

use App\Override\Model;

class IP extends Model
{
  const CREATED_AT = null;

  public $table = 'ip';

  public $timestamps = false;

  public $primaryKey = 'id';

  public $casts = [
    'updated_at' => 'timestamp'
  ];
}
