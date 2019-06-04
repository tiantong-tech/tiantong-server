<?php

namespace App\Exceptions;

class CustomerExpection extends \Exception
{
	public $response;

	public function __construct($response)
	{
		$this->response = $response;
	}
}
