<?php

namespace App\Models;

class News extends _Model
{
  public $table = "news";

  public $timestamps = false;

	protected $casts = [

	];

	protected $fillable = [
    'title', 'article', 'is_visible'
  ];

  public function scopeOnlyVisible($query)
  {
    return $query->where('is_visible', true);
  }

  public function scopeWithSearch($query, $search)
  {
    $columns = [
      'title', 'article'
    ];

    foreach ($columns as $column) {
      $query->orWhere($column, 'like', "%$search%");
    }

    return $query;
  }
}
