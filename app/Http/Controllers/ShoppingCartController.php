<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
    public function index(): JsonResponse
    {
        $cart = ShoppingCart::with('items.variant.product')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty',
                'cart_items' => []
            ]);
        }

        return response()->json([
            'cart_items' => $cart->items,
            'total' => $cart->items->sum(function($item) {
                return $item->quantity * $item->variant->price;
            })
        ],200);
    }


    public function add(Request $request):JsonResponse
    {
        $validatedData = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::findOrFail($validatedData['variant_id']);

        $cart = ShoppingCart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $cartItem = CartItem::where('shopping_cart_id', $cart->id)
            ->where('variant_id', $validatedData['variant_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validatedData['quantity'];
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'shopping_cart_id' => $cart->id,
                'variant_id' => $validatedData['variant_id'],
                'quantity' => $validatedData['quantity'],
                'unit_price' => $variant->product->price,
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_item' => $cartItem
        ], 201);
    }


    public function update(Request $request, $cartItemId):JsonResponse
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $cartItemId)
            ->whereHas('shoppingCart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $cartItem->update([
            'quantity' => $validatedData['quantity']
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $cartItem
        ],200);
    }

    public function remove($cartItemId):JsonResponse
    {
        $cartItem = CartItem::where('id', $cartItemId)
            ->whereHas('shoppingCart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully'
        ],200);
    }

}
