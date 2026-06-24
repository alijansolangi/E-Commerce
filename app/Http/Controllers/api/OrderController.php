<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->count() == 0) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        $total = 0;

        $order = Order::create([
            'user_id' => $user->id,
            'total' => 0, // later update
            'status' => 'pending'
        ]);

        foreach ($cart->items as $item) {

            $price = $item->product->price;
            $subtotal = $price * $item->quantity;

            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $price
            ]);
        }

        $order->update([
            'total' => $total
        ]);

        $cart->items()->delete();

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
            'total' => $total
        ]);
    }
}
