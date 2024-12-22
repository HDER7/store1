<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;

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
        $product = Product::all()->findOrFail($productId);
        $product->other_attributes = json_decode($product->other_attributes,true);
        return response()->json($product,200);
    }

    public function search(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }


        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }


        if ($request->has('color') || $request->has('size')) {
            $query->whereHas('variants', function (Builder $query) use ($request) {
                if ($request->has('color')) {
                    $query->where('color', 'like', '%' . $request->color . '%');
                }
                if ($request->has('size')) {
                    $query->where('size', 'like', '%' . $request->size . '%');
                }
            });
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        return response()->json([
            'data' => $products
        ]);
    }
}
