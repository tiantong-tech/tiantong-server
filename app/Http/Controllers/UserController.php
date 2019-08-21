<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Enums;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function create()
  {
    $name = $this->get('name', 'nullable');
    $email = $this->get('email', 'nullable|email');
    $username = $this->get('username', 'required');
    $password = $this->get('password', 'required|string');
    $type = $this->get('type', Rule::in(Enums::groups));
    $id = $this->get('id', 'nullable|integer');

    $user = new User();
    $user->type = $type;
    $user->email = $email;
    $user->name = $name;
    $user->password = $password;
    $user->username = $username;
    if ($id) $user->id = $id;
    $user->save();

    return $this->success('success_to_create_user');
  }

  public function delete()
  {
    $id = $this->get('id', 'integer');
    $ids = $this->get('ids', 'array|nullable');

    if ($id) {
      User::withoutRoot()->where('id', $id)->delete();
    } else if ($ids) {
      User::withoutRoot()->whereIn('id', $ids)->delete();
    }

    return $this->success('success_to_delete_account');
  }

  public function search()
  {
    $search = $this->get('search', 'nullable|string');
    $query = User::orderBy('created_at', 'desc');
    if ($search) {
      $query->withSearch($search);
    }

    return $query->paginate();
  }

  public function getUsers()
  {
    $selects = [
      'id', 'name', 'email',
      'username', 'groups', 'phone_number',
    ];

    return User::select($selects)->get();
  }
}
