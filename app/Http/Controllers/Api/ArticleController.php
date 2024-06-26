<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\Article;


class ArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'only' => ['store', 'update', 'destroy']
        ]);
    }
    
    public function index(): ArticleCollection
    {
        $articles = Article::query()
            ->allowedFilters(['title', 'content', 'year', 'month'])
            ->allowedSorts(['title', 'content'])
            ->jsonPaginate();

        return ArticleCollection::make($articles);
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }


    public function store(SaveArticleRequest $request): ArticleResource
    {
        $article = Article::create($request->validated() + ['user_id' =>auth()->id()]);
        return ArticleResource::make($article);
    }

    public function update(Article $article, SaveArticleRequest $request): ArticleResource
    {
        $article->update($request->validated());
        return ArticleResource::make($article);
    }

    public function destroy(Article $article): Response
    {
        $article->delete();
        return response()->noContent();
    }

}
