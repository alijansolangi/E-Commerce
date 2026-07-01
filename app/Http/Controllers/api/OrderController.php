<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        // dd('Checkout Hit');

        $cart = Cart::where('user_id', $user->id)
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        // DB::beginTransaction();

        try {

            $total = 0;

            // Stock Check
            foreach ($cart->items as $item) {

                if (!$item->product) {
                    DB::rollBack();

                    return response()->json([
                        'message' => 'Product not found.'
                    ], 404);
                }

                if ($item->quantity > $item->product->stock) {
                    DB::rollBack();

                    return response()->json([
                        'message' => $item->product->name . ' has only ' . $item->product->stock . ' items available.'
                    ], 422);
                }
            }

            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'pending'
            ]);

            // Save Order Items


            $price = $item->product->price;
            $subtotal = $price * $item->quantity;

            $total += $subtotal;
            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price
                ]);


                // Reduce Stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Update Order Total
            $order->update([
                'total' => $total
            ]);

            // return response()->json([
            //     'user' => $total,
            // ]);

            // Clear Cart
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'total' => $total,
                'status' => $order->status
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($orders);
    }
    public function show(Request $request, Order $order)
    {
        if ($order->user_id != $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $order->load('items.product');

        return response()->json($order);
    }
}
