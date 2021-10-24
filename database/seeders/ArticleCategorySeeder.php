<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{

//    Выполняется около 15 минут, не знаю как сделать это более оптимально

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = Article::all();
        $categories = Category::all();
        foreach ($articles as $article) {
            $randCategory = $categories->random()->id;
            ArticleCategory::factory()->create(
                [
                    'article_id' => $article->id,
                    'category_id' => $randCategory,
                ]
            );
        }
        $articles = Article::where('id', '<=', '9999')->where('id', '>=', '8000')->get();
        foreach ($articles as $article) {  // добавление второй связи к категории для статей с 8000 по 9999
            $randCategory = $categories->random()->id;
            ArticleCategory::factory()->create(
                [
                    'article_id' => $article->id,
                    'category_id' => $randCategory,
                ]
            );
        }
    }
}
