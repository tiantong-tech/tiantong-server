<?php

namespace App\Http\Middleware;

use JWT;
use Auth;
use Closure;

class Authenticate
{
	public function handle($request, Closure $next)
	{
		$token = request()->header('authorization');
		$payload = JWT::decode($token);
		if (!$token || !$payload) {
			return response([
				'message' => 'fail_to_authenticate_token'
			], 401);
		};

		Auth::findUser($payload['aud']);
		if (!Auth::user()) {
			return response([
				'message' => 'fail_to_find_user_by_token'
			]);
		}

		return $next($request);
	}
}
