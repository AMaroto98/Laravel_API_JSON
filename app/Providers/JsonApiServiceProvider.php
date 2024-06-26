<?php

namespace App\Providers;

use App\JsonApi\JsonApiQueryBuilder;
use App\JsonApi\JsonApiTestResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;

class JsonApiServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }


    public function boot(): void
    {
        Builder::mixin(new JsonApiQueryBuilder());
        TestResponse::mixin(new JsonApiTestResponse);
    }
}
