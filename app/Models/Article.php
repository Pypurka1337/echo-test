<?php

namespace App\Models;

use App\Traits\Models\HasFieldsSelecting;
use App\Traits\Models\HasFiltering;
use App\Traits\Models\HasPagination;
use App\Traits\Models\HasSorting;
use App\Traits\Models\RouteBindingSlugOrId;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Article extends Model
{
    use HasFactory;
    use Sluggable;
    use RouteBindingSlugOrId;
    use HasSorting;
    use HasFiltering;
    use HasFieldsSelecting;
    use HasPagination;

    protected $with = ['author', 'categories'];

    protected $fillable = [
        'name',
        'image',
        'preview_text',
        'detail_text',
        'author_id',
        'slug',
    ];


    public function changeCategories(array $categoryIds)
    {
        ArticleCategory::where('article_id', $this->id)->delete();

        foreach ($categoryIds as $categoryId) {
            ArticleCategory::add($this->id, $categoryId);
        }
    }

    public static function whereAuthor(Request $request, Builder $builder)
    {
        $author = $request->query('authors');
        if ($author) {
            return $builder->whereHas('author', function ($query) use ($author) {
                $query->where($author);
            });
        }
        return $builder;
    }

    public static function whereCategories(Request $request, Builder $builder)
    {
        $categories = $request->query('categories');
        if ($categories) {
            return $builder->whereHas('categories', function ($query) use ($categories) {
                $query->where($categories);
            });
        }
        return $builder;
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
