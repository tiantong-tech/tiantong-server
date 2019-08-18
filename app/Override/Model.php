<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
  public $timestamps = false;

  public function newModelQuery ()
  {
    $builder = new Builder($this->newBaseQueryBuilder());

    return $builder->setModel($this);
  }

  protected function castAttribute($key, $value)
  {
    switch ($this->getCastType($key)) {
      case 'array':
        return $this->getArray($value);
      default:
        return parent::castAttribute($key, $value);
    }
  }

  public function setAttribute($key, $value)
  {
    switch ($this->getCastTypeByKey($key)) {
      case 'array':
        $this->attributes[$key] = $this->setArray($value);
        break;
      default:
        parent::setAttribute($key, $value);
    }

    return $this;
  }

  protected function getCastTypeByKey($key)
  {
    if (isset($this->casts[$key])) {
      return $this->casts[$key];
    } else {
      return null;
    }
  }

  protected function setArray($data, $type = 'int')
  {
    return '{'.implode(',', $data).'}';
  }

  protected function getArray($data, $type = 'int')
  {
    return json_decode('['.substr($data, 1, -1).']', true);
  }

}
