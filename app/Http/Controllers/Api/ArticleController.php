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
    public function index(): ArticleCollection
    {
        $articles = Article::allowedSorts(['title', 'content']);

        return ArticleCollection::make($articles->paginate(
            $perPage = request('page.size', 15),
            $columns = ['*'],
            $pageName = 'page[number]',
            $page = request('page.number', 1)
        )->appends(request()->only('sort', 'page.size')));
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }


    public function store(SaveArticleRequest $request): ArticleResource
    {
        $article = Article::create($request->validated());
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
