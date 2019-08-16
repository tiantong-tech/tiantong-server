<?php

namespace App\Http\Controllers;

use Auth;
use Qiniu;
use Carbon\Carbon;
use App\Models\File;
use App\Exceptions\HttpException;

class FileController extends Controller
{
  public function update()
  {
    $data = $this->via([
      'name' => 'string'
    ]);
    $file = $this->getFile();
    $file->fill($data);
    $file->save();

    return $this->success('success_to_update_file_data');
  }

  public function confirmUpload()
  {
    $link = $this->get('link', 'required');
    $file = $this->getFile();
    $file->link = $link;
    $file->status = '已上传';
    $file->save();

    return $this->success('success_to_confirm_file_upload');
  }

  protected function getFile()
  {
    $id = $this->get('id');
    $userId = Auth::user()->id;
    $file = File::find($id);
    if (!$file) {
      throw new HttpException('fail to find file by id', 404);
    }

    if ($file->user_id !== $userId) {
      throw new HttpException('fail to authenticate file by user id', 401);
    }

    return $file;
  }
}
