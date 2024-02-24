<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Responses\JsonApiValidationErrorResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception): JsonResponse
    {
        if (!$request->routeIs('api.v1.login')) {
            return new JsonApiValidationErrorResponse($exception);
        }

        return parent::invalidJson($request, $exception);
    }

}
