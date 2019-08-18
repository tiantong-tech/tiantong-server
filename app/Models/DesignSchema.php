<?php

namespace App\Models;

use App\Override\Model;

class DesignSchema extends Model
{
  public $table = 'design_schemas';

  public $timestamps = true;
  const UPDATED_AT = null;

  protected $fillable = [
    'type', 'name', 'data', 'quantity'
  ];

  protected $casts = [
    'quotation_ids' => 'array',
    'cad_drawing_ids' => 'array',
  ];
}
