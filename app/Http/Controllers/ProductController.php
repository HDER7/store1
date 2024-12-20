<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(Request $request):JsonResponse
    {
        $products = Product::with('variants');

        if ($request->has('min_price')) {
            $products->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $products->where('price', '<=', $request->input('max_price'));
        }

        $perPage = $request->input('per_page', 15);
        $products = $products->paginate($perPage);

        $products->transform(function ($product) {
            $product->other_attributes = json_decode($product->other_attributes, true);
            return $product;
        });

        return response()->json($products,200);
    }

    public function show($productId):JsonResponse
    {
        $product = Product::all()->find($productId);
        $product->other_attributes = json_decode($product->other_attributes,true);
        return response()->json($product,200);
    }
}
