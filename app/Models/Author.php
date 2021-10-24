<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Author extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'avatar',
        'birth_year',
        'biography',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
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
