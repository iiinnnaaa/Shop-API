<?php

namespace App\Http\Filters;

class MaxPriceFilter {
    public function filter($builder, $value){
        return $builder->where('price', '<=', $value);
    }
}
