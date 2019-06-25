<?php

namespace App\Models;

use Hash;
use Series;
use App\Override\Model;
// use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
	// use Notifiable;

  public $table = "users";

  public $timestamps = false;

  public $casts = [
    'groups' => 'array'
  ];

	protected $fillable = [
    'name',
	];

  protected $hidden = [
		'password',
	];

  // 根据 type 类型自动推断 id 及 groups
  public function setTypeAttribute($group)
  {
    $this->attributes['groups'] = json_encode([$group]);
    $this->attributes['id'] = Series::generate($group . '_id');
  }

  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = Hash::make($password);
  }

  public function scopeWithSearch($query, $search)
  {
    $columns = ['username', 'email', 'name', 'id'];
    foreach ($columns as $column) {
      $query->orWhere($column, 'like', "%$search%");
    }

    return $query;
  }

  public function scopeWithoutRoot($query)
  {
    return $query->whereRaw('(groups @> \'"root"\') = false');
  }

  public function scopeIsRole($query, $role)
  {
    return $query->where('role', $role);
  }
}
