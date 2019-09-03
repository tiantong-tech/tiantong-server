<?php

namespace App\Http\Controllers;

class AppController extends _Controller
{
  public function home()
  {
    return [
      'msg' => '天瞳系统 v1.0'
    ];
  }
}
