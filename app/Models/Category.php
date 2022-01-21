<?php

namespace App\Models;

use App\Traits\RouteBindingSlugOrId;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory;
    use NodeTrait;
    use Sluggable;
    use RouteBindingSlugOrId;

    // Решение конфликта Sluggable и NodeTrait
    protected $fillable = [
        'name',
        'image',
        'description',
        'slug',
    ];

    public function replicate(array $except = null)
    {
        $instance = $this->replicateNode($except);
        (new SlugService())->slug($instance, true);

        return $instance;
    }

    /**
     * Получить статьи категории.
     */
    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_category',
            'category_id',
            'article_id'
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
