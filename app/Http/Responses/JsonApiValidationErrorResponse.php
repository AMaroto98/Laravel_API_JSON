<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class JsonApiValidationErrorResponse extends JsonResponse
{

    public function __construct(ValidationException $exception, $status = 422)
    {

        // $title = ($exception->getMessage());
        // $errors = [];
        // foreach ($exception->errors() as $field => $messages) {
        //     $pointer = "/" . str_replace('.', '/', $field);

        //     $errors[] = [
        //         'title' => $title,
        //         'detail' => $messages[0],
        //         'source' => [
        //             'pointer' => $pointer
        //         ]
        //     ];
        // }

        $data = $this->formatJsonApiErrors($exception);

        $headers = [
            'content-type' => 'application/vnd.api+json'
        ];

        parent::__construct($data, $status, $headers, 0);
    }

    /**
     * @param ValidationException $exception
     * @return array
     */
    public function formatJsonApiErrors(ValidationException $exception): array
    {
        $title = $exception->getMessage();

        return [
            'errors' => collect($exception->errors())
                ->map(function ($messages, $field) use ($title) {
                    return [
                        'title' => $title,
                        'detail' => $messages[0],
                        'source' => [
                            'pointer' => "/" . str_replace('.', '/', $field)
                        ]
                    ];
                })->values()
        ];
    }
}
