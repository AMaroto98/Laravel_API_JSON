<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Dotenv\Exception\ValidationException;
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

    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception)
    {
        $title = ($exception->getMessage());
        $errors = [];
        foreach ($exception->errors() as $field => $messages) {
            $pointer = "/" . str_replace('.', '/', $field);

            $errors[] = [
                'title' => $title,
                'detail' => $messages[0],
                'source' => [
                    'pointer' => $pointer
                ]
            ];
        }

        return response()->json([
            'errors' => $errors
        ], 422, [
            'content-type' => 'application/vnd.api+json'
        ]);
    }

}
