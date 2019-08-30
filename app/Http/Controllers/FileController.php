<?php

namespace App\Http\Controllers;

use Auth;
use Qiniu;
use Carbon\Carbon;
use App\Models\File;
use App\Exceptions\HttpException;
use App\Http\Controllers\Traits;

class FileController extends Controller
{
  use Traits\FileTrait;

  public function update()
  {
    $data = $this->via([
      'name' => 'string',
      'comment' => 'string',
    ]);
    $file = $this->getFile();
    $file->fill($data);
    $file->save();

    return $this->success('success_to_update_file_data');
  }
}
