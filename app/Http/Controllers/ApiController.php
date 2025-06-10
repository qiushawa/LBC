<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\ProductCategorie;

class ApiController extends Controller
{
    /**
     * Example index method.
     */
    public function status(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'API Controller index method.',
        ]);
    }

    // 使用產品類別查詢產品
    public function productsByCategory(Request $request): JsonResponse
    {
        $categoryId = $request->query('id');
        $category = ProductCategorie::find($categoryId);

        if (!$category) {
            return response()->json([
                'error' => 'Category not found',
            ], 404);
        }

        $products = $category->products()->get();
        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}
