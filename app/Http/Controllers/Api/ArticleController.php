<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    // Obetener un artículo
    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    // Obtener todos los artículos
    public function index(): ArticleCollection
    {
        return ArticleCollection::make(Article::all());
    }

    // Crear un articulo
    public function store(Request $request)
    {

        $request->validate([
            'data.attributes.title' => ['required', 'min:4'],
            'data.attributes.slug' => ['required'],
            'data.attributes.content' => ['required'],
        ]);

        $article = Article::create([
            'title' => $request->input('data.attributes.title'),
            'slug' => $request->input('data.attributes.slug'),
            'content' => $request->input('data.attributes.content'),
        ]);

        return ArticleResource::make($article);
    }

}
