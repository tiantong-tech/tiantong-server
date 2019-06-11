<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
  public function home()
  {
    return [
      'message' => '天瞳系统 v1.0'
    ];
  }
}
