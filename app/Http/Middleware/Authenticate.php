<?php

namespace App\Http\Middleware;

use JWT;
use Auth;
use Closure;

class Authenticate extends Middleware
{
	public function handle($request, Closure $next, ...$groups)
	{
		$token = request()->header('authorization');
		$payload = JWT::decode($token);
		if (!$token || !$payload) {
			return response([
				'message' => 'fail_to_authenticate_token'
			], 401);
		};

		$user = Auth::findUser($payload['aud']);

		if (!$user) {
			return response([
				'message' => 'fail_to_find_user_by_token'
			], 401);
		}

		if (sizeof($groups)) {
			foreach ($user->groups as $group) {
				if (in_array($group, $groups)) {
					return $next($request);
				}
			}

			return $this->failure('fail_to_validate_user_role', 401);
		}

		return $next($request);
	}
}
