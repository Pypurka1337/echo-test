<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasSorting
{
    public static function doSorting(Request $request, Builder $builder): void
    {
        $sort = $request->query('sort');
        if ($sort) {
            foreach ($sort as $value) {
                $is_asc = (substr($value, 0, 1) !== '-');
                if ($is_asc) {
                    $builder->orderBy($value);
                } else {
                    $value = substr($value, 1);
                    $builder->orderByDesc($value);
                }
            }
        }
    }
}
