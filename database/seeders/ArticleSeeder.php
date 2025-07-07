<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Article::create([
                'title' => "Sample Article $i",
                'content' => "This is the content of article number $i. It contains interesting information.",
                'image' => null,
                'published' => $i % 2 === 0, // only even-numbered articles are published
            ]);
        }
    }
}
