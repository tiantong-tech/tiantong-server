<?php

namespace App\Models;

class Activity extends _Model
{
  public $table = 'activities';

  protected $fillable = [
    'type', 'name', 'notes'
  ];
}
