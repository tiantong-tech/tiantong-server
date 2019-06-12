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

	protected $fillable = [
    'name',
	];

  protected $hidden = [
		'password', 'created_at',
	];

  // 根据 type 类型自动设置 id 及 groups
  public function setAutoRoleAttribute($role)
  {
    $this->attributes['role'] = $role;
    $this->attributes['id'] = Series::generate($role . '_id');
  }

  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = Hash::make($password);
  }

  public function scopeIsRole($query, $role)
  {
    return $query->where('role', $role);
  }
}
