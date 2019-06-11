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

  public function loginByEmail()
  {
    $password = $this->get('password', 'string');
    $where = ['email' => $this->get('email', 'email')];

    $token = Auth::matchPassword($password, $where);
    if (!$token) {
      return $this->failure('fail_to_login_by_email', 401);
    }

    return $this->success([
      'message' => 'success_to_login',
      'token' => $token
    ]);
  }
}
