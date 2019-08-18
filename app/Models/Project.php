<?php

namespace App\Models;

use App\Override\Model;

class Project extends Model
{
  public $table = 'projects';

  public $timestamps = true;
  const UPDATED_AT = null;

  protected $fillable = [
    'type', 'name', 'company',
    'customer_information', 'notes',
    'delivery_date', 'signature_date',
    'member_ids', 'status',
  ];

  protected $casts = [
    'status' => 'array',
    'file_ids' => 'array',
    'member_ids' => 'array',
    'activity_ids' => 'array',
    'design_schema_ids' => 'array',
  ];

  public function design_schemas()
  {
    return $this->hasMany(DesignSchema::class);
  }
}
