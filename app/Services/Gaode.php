<?php

namespace App\Services;

use GuzzleHttp\Client;

class Gaode
{
  protected $webkey;

  public function __construct()
  {
    $config = config('services.gaode');

    $this->webkey = $config['web.key'];
  }

  public function getIpLocation($ip)
  {
    $client = new Client();
    $data = $client
      ->get("https://restapi.amap.com/v3/ip?key=$this->webkey&ip=$ip")
      ->getBody()
      ->getContents();
    $data = json_decode($data);
    foreach (['province', 'city'] as $key) {
      if (gettype($data->$key) !== 'string') {
        $data->$key = '';
      }
    }

    return $data;
  }
}
