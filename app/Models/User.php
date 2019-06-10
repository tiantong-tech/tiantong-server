<?php

namespace App\Models;

use Hash;
use Series;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
	// use Notifiable;

  public $table = "users";

  public $timestamps = false;

	protected $casts = [
    'groups' => 'array',
	];

	protected $fillable = [
    'name',
	];

  protected $hidden = [
		'password', 'created_at',
	];

  // 根据 type 类型自动设置 id 及 groups
  public function setTypeAttribute($type)
  {
    $this->attributes['groups'] = json_encode([$type]);
    $this->attributes['id'] = Series::generate($type . '_id');
  }

  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = Hash::make($password);
  }

  public function scopeGroups($query, $groups)
  {
    if (!is_array($groups)) {
      $groups = json_encode([$groups]);
    }

    return $query->where('groups', '@>', $groups);
  }
}
