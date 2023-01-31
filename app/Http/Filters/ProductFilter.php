<?php

namespace App\Http\Filters;

class ProductFilter extends AbstractFilter{
    protected $filters = [
        'min'=>MinPriceFilrer::class,
        'max'=>MaxPriceFilter::class,
        'text'=>TextFilter::class,
        'category'=>CategoryFilter::class,
    ];
}
