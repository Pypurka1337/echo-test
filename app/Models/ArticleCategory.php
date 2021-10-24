<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleCategory extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'category_id',
    ];

    /**
     * Создать связь статьи и категории
     *
     * @param int $articleId
     * @param int $categoryId
     * @return mixed
     */
    public static function add(int $articleId, int $categoryId)
    {
        return ArticleCategory::create([
            'article_id' => $articleId,
            'category_id' => $categoryId,
        ]);
    }

}
