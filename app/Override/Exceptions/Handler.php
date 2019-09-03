<?php

namespace App\Override\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
  protected $dontReport = [
    HttpException::class
  ];

	public function report(Exception $exception)
	{
		parent::report($exception);
	}

	public function render($request, Exception $e)
	{
    if ($e instanceof NotFoundHttpException) {
      return $this->handleNotfound();
    }

		if ($e instanceof ValidationException) {
      return $this->handleValidationException($e);
    }

		return parent::render($request, $e);
  }

  // handlers

  private function handleNotfound()
  {
    return response()->json([
      'msg' => 'route not found',
    ], 404);
  }

  private function handleValidationException($e)
  {
    return response()->json([
      'errors' => $e->errors(),
      'params' => request()->input(),
    ], 422);
  }
}
