<?php

namespace App\Models;

use App\Traits\Models\HasFieldsSelecting;
use App\Traits\Models\HasFiltering;
use App\Traits\Models\HasPagination;
use App\Traits\Models\HasSorting;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Models\RouteBindingSlugOrId;
use Illuminate\Http\Request;


class Author extends Model
{
    use HasFactory;
    use Sluggable;
    use RouteBindingSlugOrId;
    use HasSorting;
    use HasFiltering;
    use HasFieldsSelecting;
    use HasPagination;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'avatar',
        'birth_year',
        'biography',
        'slug',
    ];

    public static function whereArticles(Request $request, Builder $builder)
    {
        $articles = $request->query('articles');
        if ($articles) {
            return $builder->whereHas('articles', function ($query) use ($articles) {
                $query->where($articles);
            });
        }
        return $builder;
    }

    /**
     * Получить статьи принадлежащие автору
     *
     * @return HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'fullname',
            ],
        ];
    }

    public function getFullnameAttribute(): string
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }

}
