<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function viaParams($params, $rules)
	{
		$validator = \Validator::make($params, $rules);

		if ($validator->fails()) {
			throw new \Illuminate\Validation\ValidationException($validator);
		}

		$result = [];

		foreach ($rules as $key => $value) {
			if (isset($params[$key])) {
				$result[$key] = $params[$key];
			} else {
				$result[$key] = null;
			}
		}

		return $result;
	}

	public function via(array $rules)
	{
		$params = request(array_keys($rules));

		return $this->viaParams($params, $rules);
	}

	public function all()
	{
		return request()->all();
	}

	public function get($name, $rule = 'nullable', $default = null)
	{
		$params = request([$name]);
		$this->viaParams($params, [$name => $rule]);
		$param = isset($params[$name]) ? $params[$name] : $default;

		return $param;
	}

	public function success($messages, $status = 201)
	{
		if (!is_array($messages)) {
			$messages = ['message' => $messages];
		}

		return response()->json($messages, $status);
	}

	public function failure($messages, $status = 400)
	{
		if (!is_array($messages)) {
			$messages = ['message' => $messages];
		}

		throw new \App\Exceptions\CustomerExpection(
			response()->json($messages, $status)
    );
	}
}
