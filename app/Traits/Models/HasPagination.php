<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasPagination
{
    public static function makePaginate(Request $request, Builder $builder)
    {
        return $builder->paginate($request->input('size', 10))
            ->withQueryString();
    }
}
