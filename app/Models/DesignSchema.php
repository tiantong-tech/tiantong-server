<?php

namespace App\Models;

use App\Override\Model;

class DesignSchema extends Model
{
  public $table = 'design_schemas';

  public $timestamps = true;
  const UPDATED_AT = null;

  protected $fillable = [
    'type', 'name', 'data', 'quantity', 'notes',
    'is_completed'
  ];

  protected $casts = [
    'data' => 'json',
    'quotation_ids' => 'array',
  ];

  public function cad_drawing()
  {
    return $this->hasOne(CadDrawing::class);
  }

  public function quotations()
  {
    return $this->hasMany(Quotation::class);
  }
}
