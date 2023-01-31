<?php

namespace App\Http\Filters;

class MinPriceFilrer {
    public function filter($builder, $value){
        return $builder->where('price', '>=', $value);
    }
}
