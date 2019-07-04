<?php

namespace App\Models;

use App\Override\Model;

class SaleTrack extends Model
{
	// use Notifiable;

  public $table = "sale_tracks";

  public $timestamps = false;

	protected $casts = [
    'data' => 'json',
	];

	protected $fillable = [
    'type', 'status', 'name',
    'phone', 'email', 'message', 'data',
    'company', 'phone_number', 'project_name'
  ];

  public function scopeWithSearch($query, $search)
  {
    $columns = [
      'name', 'email', 'message',
      'data', 'company', 'phone_number'
    ];

    foreach ($columns as $column) {
      $query->orWhere($column, 'like', "%$search%");
    }

    return $query;
  }
}
