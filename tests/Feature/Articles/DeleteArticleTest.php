<?php

namespace Tests\Feature\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use App\Models\Article;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_articles(): void
    {
        $artcile = Article::factory()->create();
        $this->deleteJson(route('api.v1.articles.destroy', $artcile))->assertUnauthorized();
    }

    /** @test */
    public function can_delete_articles(): void
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->user);
        $this->deleteJson(route('api.v1.articles.destroy', $article))->assertNoContent();
        $this->assertDatabaseCount('articles', 0);
    }
}
