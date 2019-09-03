<?php

namespace App\Models;

class Quotation extends _Model
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
