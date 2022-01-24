<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasFiltering
{
    public static function selectFields(Request $request, Builder $builder): void
    {
        $filters = $request->query('filters');
        if ($filters) {
            $builder->where($filters);
        }
    }
}
