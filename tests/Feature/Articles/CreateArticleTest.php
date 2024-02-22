<?php

namespace Tests\Feature\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Article;
use Tests\TestCase;


class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo artículo',
                    'slug' => 'nuevo-artículo',
                    'content' => 'Contenido del artículo',
                ]
            ]
        ]);

        $response->assertCreated();
        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Nuevo artículo',
                    'slug' => 'nuevo-artículo',
                    'content' => 'Contenido del artículo',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ],
        ]);
    }
}
