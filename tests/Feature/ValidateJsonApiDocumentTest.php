<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ValidateJsonApiDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
        $this->withoutJsonApiDocumentFormatting();
        Route::any('test_route', function () {
            return 'OK';
        })->middleware(ValidateJsonApiDocument::class);
    }

    /** @test */
    public function only_accepts_valid_json_api_document(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertSuccessful();

        $this->patchJson('test_route', [
            'data' => [
                'id' => '1',
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertSuccessful();


    }

    /** @test */
    public function data_is_required(): void
    {
        $this->postJson('test_route', [])
            ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_route', [])
            ->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function data_must_be_an_array(): void
    {
        $this->postJson('test_route', [
            'data' => 'string'
        ])
            ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_route', [
            'data' => 'string'
        ])
            ->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function data_type_is_required(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'attributes' => [
                    'name' => 'value'
                ]
            ]
        ])->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_route', [
            'data' => [
                'attributes' => [
                    'name' => 'value'
                ]
            ]
        ])->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_type_must_be_a_string(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 1,
                'attributes' => ['name' => 'value']
            ]
        ])
            ->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 1,
                'attributes' => ['name' => 'value']
            ]
        ])
            ->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_attribute_is_required(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string'
            ]
        ])
            ->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string'
            ]
        ])
            ->assertJsonApiValidationErrors('data.attributes');
    }

    /** @test */
    public function data_attribute_must_be_an_array(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])
            ->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])
            ->assertJsonApiValidationErrors('data.attributes');
    }

    /** @test */
    public function data_id_is_required(): void
    {
        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])
            ->assertJsonApiValidationErrors('data.id');
    }

    /** @test */
    public function data_id_must_be_a_string(): void
    {
        $this->patchJson('test_route', [
            'data' => [
                'id' => 1,
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])
            ->assertJsonApiValidationErrors('data.id');
    }
}
