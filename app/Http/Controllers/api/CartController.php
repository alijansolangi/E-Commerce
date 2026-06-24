<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->with(['items.product'])
            ->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty',
                'items' => [],
                'total' => 0
            ]);
        }

        $total = 0;

        $items = $cart->items->map(function ($item) use (&$total) {

            $price = $item->product->price;
            $qty = $item->quantity;

            $subtotal = $price * $qty;

            $total += $subtotal;

            return [
                'id' => $item->id,
                'product_name' => $item->product->name,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        });

        return response()->json([
            'items' => $items,
            'total' => $total
        ]);
    }
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $user = $request->user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {

            $cartItem->quantity += $request->quantity ?? 1;
            $cartItem->save();
        } else {

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }
        return response()->json([
            'message' => 'Product added to cart successfully'
        ]);
    }
    public function remove(Request $request, $id)
    {
        $user = $request->user();

        // user ka cart lo
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found'
            ], 404);
        }

        // cart item find karo
        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        // delete item
        $item->delete();

        return response()->json([
            'message' => 'Item removed from cart'
        ]);
    }
}
