<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AuthSale extends Middleware
{
  protected $roles = ['sale', 'admin', 'root'];

	public function handle($request, Closure $next)
  {
    $user = Auth::user();
    if (!in_array($user->role, $this->roles)) {
      return $this->failure('fail_to_validate_sale_role', 401);
    }

    return $next($request);
  }
}
