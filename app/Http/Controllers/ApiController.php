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
        // 獲取該類別下的所有上架產品
        $products = $category->products()->where('launch_status', 'active')->get();
        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }







    /**
     * 獲取上線人數
     */
    public function getOnlineUsers(Request $request): JsonResponse
    {
        // from ServiceProvider
        $onlineUsers = app('cache')->get('online_users', 0);
        return response()->json([
            'online_users' => $onlineUsers,
        ]);
    }
}
