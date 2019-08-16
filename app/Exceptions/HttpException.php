<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
	public $response;

	public function __construct($messages, $status = 400)
	{
		if (!is_array($messages)) {
			$messages = ['message' => $messages];
		}

		$this->response = response()->json($messages, $status);
	}
}
