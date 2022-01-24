<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasFieldsSelecting
{
    public static function doFiltering(Request $request, Builder $builder): void
    {
        $fields = $request->query('fields');
        if ($fields) {
            $fields[] = 'id';
            $fields = in_array('id', $fields) ? $fields : $fields[] = 'id';
            $builder->select($fields);
        }
    }
}
