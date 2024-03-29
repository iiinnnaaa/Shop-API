<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class CartController extends Controller
{
    public function listItems(Product $product, User $id)
    {
        return Product::query()->with('cart')->has('cart')->get();
    }

    public function addToCart(Product $product)
    {
        try {
            $cart = Cart::query()->create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);

            $product->query()->where('id', $product->id)->update([
                'cart_id' => $cart->id,
            ]);

            return $this->responseBody(message: "$product->name added to your cart successfully.");

        } catch (\Exception $e) {
            return $this->responseBody(false, $e->getMessage(), 500);
        }
    }

    public function removeFromCart(Product $product)
    {
        Cart::query()->where('product_id', $product->id)->delete();

        $product->query()->where('id', $product->id)->update([
            'cart_id' => null,
        ]);

        return $this->responseBody(message: "$product->name successfully removed from your cart.");
    }

    public function emptyCart()
    {
        Cart::query()->where('user_id', auth()->id())->delete();

        return $this->responseBody(message: "All items are removed from your cart successfully.");
    }

    public function purchaseItems()
    {
        $products = Product::query()->with('cart')->has('cart')->get()->all();

        $order = Order::query()->create([
            'user_id' => auth()->id(),
        ]);

        $price = 0;
        foreach ($products as $product) {
            $product->orders()->attach($order->id);
            $price += $product->price;
        }

        $order->update(['total' => $price]);

        Cart::query()->where('user_id', auth()->id())->delete();

        return $this->responseBody(message: "Order created successfully");
    }
}
