<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('articles', [ArticleController::class, 'index'])->name('api.v1.articles.index');

Route::get('article/{article}', [ArticleController::class, 'show'])->name('api.v1.articles.show');

Route::post('articles', [ArticleController::class, 'store'])->name('api.v1.articles.store');

Route::patch('article/{article}', [ArticleController::class, 'update'])->name('api.v1.articles.update');



