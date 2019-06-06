<?php

namespace App\Http\Controllers;

use Auth;

class PersonController extends Controller
{
  public function search()
  {
    $user = Auth::user();

    return $user;
  }

  public function update()
  {
    $user = Auth::user();
    $user->fill($this->all());

    return $this->success('success_to_update_user');
  }
}
