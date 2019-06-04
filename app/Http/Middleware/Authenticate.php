<?php

namespace App\Http\Middleware;

use JWT;
use Auth;
use Closure;

class Authenticate
{
	public function handle($request, Closure $next, $group = null)
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

		$isRoot = in_array('root', $user->groups);
		if ($group && !$isRoot) {
			$inGroup = in_array($group, $user->groups);
			if (!$inGroup) {
				return $this->failure('fail_to_validate_user_group');
			}
		}

		return $next($request);
	}

	private function failure($message)
	{
		return response(['message' => $message], 401);
	}
}
