<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    use Sluggable;

    protected $with = ['author', 'categories'];

    protected $fillable = [
        'name',
        'image',
        'preview_text',
        'detail_text',
        'author_id',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function changeCategories(array $categoryIds)
    {
        ArticleCategory::where('article_id', $this->id)->delete();

        foreach ($categoryIds as $categoryId) {
            ArticleCategory::add($this->id, $categoryId);
        }
    }


    /**
     * Получить автора статьи.
     */
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * Получить категории статьи.
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'article_category',
            'article_id',
            'category_id'
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
