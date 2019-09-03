<?php

namespace App\Models;

class CadDrawing extends _Model
{
  public $table = 'cad_drawings';

  public $timestamps = true;
  const UPDATED_AT = null;

  protected $fillable = [
    'deadline', 'offered_at',
  ];

  protected $casts = [

  ];
}
