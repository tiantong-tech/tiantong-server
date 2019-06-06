<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  private $groups = ['sale'];

  public function create()
  {
    $email = $this->get('email', 'email');
    $password = $this->get('password', 'string');
    $type = $this->get('type', Rule::in($this->groups));
    $id = $this->get('id', 'nullable|integer');

    $user = new User();
    $user->type = $type;
    $user->email = $email;
    $user->password = $password;
    if ($id) $user->id = $id;
    $user->save();

    return $this->success('success_to_create_user');
  }

  public function delete()
  {
    $id = $this->get('id', 'integer|nullable');
    User::where('id', $id)->delete();

    return $this->success('success_to_delete_account');
  }

  public function search()
  {
    return User::all();
  }

  public function loginByEmail()
  {
    $password = $this->get('password', 'string');
    $where = ['email' => $this->get('email', 'email')];

    $token = Auth::matchPassword($password, $where);
    if (!$token) {
      return $this->fail('fail_to_login_by_email', 401);
    }

    return $this->success([
      'message' => 'success_to_login',
      'token' => $token
    ]);
  }
}
