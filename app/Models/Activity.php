<?php

namespace App\Models;

use App\Override\Model;

class Activity extends Model
{
  public $table = 'activities';

  protected $fillable = [
    'type', 'name', 'notes'
  ];
}
