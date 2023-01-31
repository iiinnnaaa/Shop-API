<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter {
    public function filter(Builder $builder, $value){

        return $builder->with('category')->whereHas('category', function (Builder $query) use ($value) {
            $query->where('name', "like","%$value%");
        });

    }
}
