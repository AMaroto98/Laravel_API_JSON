<?php

namespace Tests\Feature\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use Tests\TestCase;


class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_articles(): void
    {

        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Updated article',
            'slug' => 'updated-article',
            'content' => 'Updated content',
        ])->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Updated article',
                    'slug' => 'updated-article',
                    'content' => 'Updated content',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ],
        ]);
    }


    /** @test */
    public function title_is_required(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(
            route('api.v1.articles.update', $article),
            [
                'slug' => 'updated-article',
                'content' => 'Updated content',
                ]

        )->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function title_must_be_at_least_4_characters(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(
            route('api.v1.articles.update', $article),
            [
                'title' => 'Nue',
                'slug' => 'updated-article',
                'content' => 'Updated content',
            ]

        )->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function slug_is_required(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(
            route('api.v1.articles.update', $article),
            [
                'title' => 'Updated article',
                'content' => 'Updated content',
            ]

        )->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(
            route('api.v1.articles.update', $article),
            [
                'title' => 'Updated article',
                'slug' => 'updated-article',
            ]

        )->assertJsonApiValidationErrors('content');
    }
}