<?php

namespace App\Models;

use App\Http\Filters\ProductFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'count',
        'category_id',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function scopeFilter(Builder $builder, $request){
        return (new ProductFilter($request))->filter($builder);
    }

}
