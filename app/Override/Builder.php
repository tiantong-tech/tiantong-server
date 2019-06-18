<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
  public function paginate($pageSize = null, $columns = ['*'], $pageName = 'page', $page = null)
  {
    !$page && ($page = request('page'));
    !$pageSize && ($pageSize = request('page_size'));
    !$page && ($page = 1);
    !$pageSize && ($pageSize = 15);

    $results = ($total = $this->toBase()->getCountForPagination())
      ? $this->forPage($page, $pageSize)->get($columns)
      : $this->model->newCollection();

    return [
      'page' => $page,
      'page_size' => $pageSize,
      'total' => $total,
      'data' => $results
    ];
  }
}
