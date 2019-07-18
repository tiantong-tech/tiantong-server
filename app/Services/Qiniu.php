<?php

namespace App\Services;

use Carbon\Carbon;

class Qiniu
{
  protected $file;

  protected $bucket;

  protected $deadline;

  protected $accessKey;

  protected $secretKey;

  public function __construct()
  {
    $this->bucket = env('QINIU_BUCKET');
    $this->deadline = env('QINIU_DEADLINE');
    $this->accessKey = env('QINIU_ACCESS_KEY');
    $this->secretKey = env('QINIU_SECRET_KEY');
  }

  public function getUploadToken($bucket = null, $file = null, $deadline = null)
  {
    if (!$bucket) $bucket = $this->bucket;
    if (!$deadline) $deadline = $this->deadline;

    $scope = $bucket;
    if ($file) $scope .= ":$file";
    $deadline += Carbon::now()->timestamp;

    $data = json_encode([
      'scope' => $scope,
      'deadline' => $deadline
    ]);

    $data = $this->base64_urlSafeEncode($data);
    $sign = $this->base64_urlSafeEncode(
      hash_hmac('sha1', $data, $this->secretKey, true)
    );
    $token = "$this->accessKey:$sign:$data";

    return $token;
  }

  private function base64_urlSafeEncode($data)
  {
    $find = array('+', '/');
    $replace = array('-', '_');

    return str_replace($find, $replace, base64_encode($data));
  }
}
