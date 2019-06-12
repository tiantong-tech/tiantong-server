<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Enums;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function create()
  {
    $email = $this->get('email', 'email');
    $password = $this->get('password', 'string');
    $role = $this->get('role', Rule::in(Enums::roles));
    $id = $this->get('id', 'nullable|integer');

    $user = new User();
    $user->email = $email;
    $user->autoRole = $role;
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
}
