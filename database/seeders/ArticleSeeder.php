<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;


class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::factory()
        ->count(5)
        ->create();
    }
}
