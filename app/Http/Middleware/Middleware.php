<?php

namespace App\Http\Middleware;

class Middleware
{
	public function failure($message, $code = 400)
	{
		return response(['message' => $message], 400);
	}
}
