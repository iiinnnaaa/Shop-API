<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return ProductResource::collection(Product::with('category')->filter($request)->orderBy('id', 'asc')->paginate(5)->withQueryString());
    }

    public function store(Request $request)
    {
        return Product::query()->create($request->all());
    }


    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return new ProductResource($product);
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, 204);
    }
}
