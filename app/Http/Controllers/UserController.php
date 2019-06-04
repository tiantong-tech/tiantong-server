<?php

namespace App\Http\Controllers;

use JWT;
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

  public function loginByEmail()
  {
    $password = $this->get('password', 'string');
    $where = ['email' => $this->get('email', 'email')];
    $id = Auth::matchPassword($password, $where);

    if (!$id) {
      return $this->failure('fail_to_login', 402);
    };

    return $this->success([
      'message' => 'success_to_login',
      'token' => JWT::encode(['aud' => $id])
    ]);
  }

  public function update()
  {
    $user = Auth::user();
    $user->fill($this->all());
    $user->save();

    return $this->success('success_to_update_user');
  }

  public function getProfile()
  {
    return Auth::user();
  }

  public function search()
  {
    return User::all();
  }
}
