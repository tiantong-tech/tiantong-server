<?php

namespace App\Models;

use App\Override\Model;

class CadDrawing extends Model
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
