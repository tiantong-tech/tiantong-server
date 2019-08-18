<?php

namespace App\Models;

use App\Override\Model;

class Quotation extends Model
{
  public $table = 'quotations';

  public $timestamps = true;
  const UPDATED_AT = null;

  protected $fillable = [
    'name', 'type', 'content',
    'deadline', 'offered_at',
  ];

  protected $casts = [

  ];
}
