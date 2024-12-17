<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShoppingCart;
use App\OrderStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index():JsonResponse
    {
        $orders = Order::with('items.variant.product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'orders' => $orders,
            'total' => $orders->count()
        ],200);
    }
    public function show($orderId):JsonResponse
    {
        $order = Order::with('items.variant.product')
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return response()->json($order);
    }

    public function create(Request $request):JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'shipping_address' => 'required|string',
        ]);

        $cart = ShoppingCart::with('items.variant')
            ->where('user_id', Auth::id())
            ->firstOrFail();


        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cannot create order: Cart is empty'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $cart->items->sum(function($item) {
                    return $item->quantity * $item->unit_price;
                }),
                'order_status' => OrderStatus::PENDING,
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'order_date' => now()
            ]);


            $orderItems = [];
            foreach ($cart->items as $cartItem) {
                $orderItems[] = OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $cartItem->variant_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                ]);
            }

            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items.variant')
            ], 201);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
