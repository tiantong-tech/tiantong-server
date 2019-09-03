<?php

namespace App\Models;

/**
 * 文件上传
 * 1. 创建一条 file 数据，返回上传凭证及 file_id
 * 2. 客户端调用第三方服务执行上传
 * 3. 上传完毕后，更新 file 状态为“已上传”，上传流程结束
 * 4. 如果创建 file 数据后，未更新 file 状态，超过 1 小时则表示上传失败
 * 5. 上传失败的文件数据为废弃数据，随时可以进行清理
 */

class File extends _Model
{
  public $table = 'files';

  public $timestamps = true;
  const UPDATED_AT = null;

  protected $fillable = [
    'namespace', 'name', 'comment', 'size',
    'link', 'user_id',
    'created_at', 'deleted_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
