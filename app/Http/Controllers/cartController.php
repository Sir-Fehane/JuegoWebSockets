<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use JWTAuth;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;

        return $cart->items->load('product');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?: Cart::create(['user_id' => $user->id]);

        $product = Product::findOrFail($request->input('product_id'));
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->input('quantity', 1),
            ]);
        }

        return response()->json(['message' => 'Product added to cart']);
    }
}
