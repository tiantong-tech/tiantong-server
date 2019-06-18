<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
  public function newModelQuery ()
  {
    $builder = new Builder($this->newBaseQueryBuilder());

    return $builder->setModel($this);
  }
}
