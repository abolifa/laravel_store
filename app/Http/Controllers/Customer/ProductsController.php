<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::with('variants', 'brand', 'categories')->get();

        return response()->json($products);
    }

    public function getByCategory(Request $request): JsonResponse
    {
        $categoryIds = $request->input('category_ids');

        if (!$categoryIds || !is_array($categoryIds)) {
            return response()->json(['error' => 'Category IDs are required and should be an array'], 400);
        }

        $products = Product::with('variants', 'brand', 'categories')
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->get();

        return response()->json($products);
    }


    /**
     * Fetch products filtered by brand.
     */
    public function getByBrand(Request $request): JsonResponse
    {
        $brandId = $request->input('brand_id');

        if (!$brandId) {
            return response()->json(['error' => 'Brand ID is required'], 400);
        }

        $products = Product::with('variants', 'brand', 'categories')
            ->where('brand_id', $brandId)
            ->get();

        return response()->json($products);
    }

    public function getByProducts(Request $request): JsonResponse
    {
        $productIds = $request->input('product_ids');

        if (!$productIds || !is_array($productIds)) {
            return response()->json(['error' => 'Product IDs are required and should be an array'], 400);
        }

        $products = Product::with('variants', 'brand', 'categories')
            ->whereIn('id', $productIds)
            ->get();

        return response()->json($products);
    }
}
