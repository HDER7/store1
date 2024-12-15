<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index():JsonResponse
    {
        $products = Product::with('variants')->get();
        return response()->json($products,200);
    }

    public function show($productId):JsonResponse
    {
        $product = Product::all()->find($productId);
        return response()->json($product,200);
    }
}
