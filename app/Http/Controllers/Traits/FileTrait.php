<?php

namespace App\Http\Controllers\Traits;

use App\Models\File;
use App\Exceptions\HttpException;

trait FileTrait
{
  protected function getFile()
  {
    $id = $this->get('file_id');
    $file = File::find($id);
    if (!$file) {
      throw new HttpException('fail to find file by id', 404);
    }

    return $file;
  }
}
