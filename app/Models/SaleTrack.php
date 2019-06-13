<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTrack extends Model
{
	// use Notifiable;

  public $table = "sale_tracks";

  public $timestamps = false;

	protected $casts = [
    'data' => 'array',
	];

	protected $fillable = [
    'type', 'status', 'name',
    'phone', 'email', 'message', 'data'
	];
}
