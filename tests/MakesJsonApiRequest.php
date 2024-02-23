<?php

namespace Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Str;
use Closure;

trait MakesJsonApiRequest
{

    protected bool $formatJsonApiDocument = true;

    public function withoutJsonApiDocumentFormatting() {
        $this->formatJsonApiDocument = false;
    }

    protected function setUp(): void
    {
        parent::setUp();
        TestResponse::macro(
            'assertJsonApiValidationErrors',
            $this->assertJsonApiValidationErrors()
        );
    }
    public function json($method, $uri, array $data = [], array $headers = [], $options = 0)
    {
        $headers['accept'] = 'application/vnd.api+json';

        if ($this->formatJsonApiDocument) {
            $formatedData = $this->getFormattedData($uri, $data);
        }

        return parent::json($method, $uri, $formatedData ?? $data, $headers);
    }

    public function postJson($uri, array $data = [], array $headers = [], $options = 0)
    {
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::postJson($uri, $data, $headers);
    }

    public function patchJson($uri, array $data = [], array $headers = [], $options = 0)
    {
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::patchJson($uri, $data, $headers);
    }

    protected function assertJsonApiValidationErrors(): Closure
    {
        return function ($attribute) {

            /** @var TestResponse $this */

            $pointer =  Str::of($attribute)->startsWith('data') ? "/" . str_replace('.', '/', $attribute) : "/data/attributes/{$attribute}";

            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                ]);

            } catch (ExpectationFailedException $e) {

                PHPUnit::fail("Failed to find a JSON:API validation error for key:`{$attribute}`"
                    . PHP_EOL . PHP_EOL .
                    $e->getMessage());
            }

            try {

                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);

            } catch (ExpectationFailedException $e) {

                PHPUnit::fail("Failed to find a valid JSON:API error response`"
                    . PHP_EOL . PHP_EOL .
                    $e->getMessage());
            }

            $this->assertHeader(
                'content-type',
                'application/vnd.api+json'
            )->assertStatus(422);

        };

    }

    public function getFormattedData($uri, array $data): array
    {
        $path = parse_url($uri)['path'];
        $type = (string)Str::of($path)->after('api/v1/')->before('/');
        $id = (string)Str::of($path)->after($type)->replace('/', '');

        return [
            'data' => array_filter([
                'type' => $type,
                'id' => $id,
                'attributes' => $data
            ])

        ];
    }
}


