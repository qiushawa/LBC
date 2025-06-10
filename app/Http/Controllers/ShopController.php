<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

// database ProductCategorie
use App\Models\ProductCategorie;
use App\Models\Ad;
class ShopController extends Controller
{
    public function index(Request $request)
    {   // 所有產品類別列表
        $categories = ProductCategorie::all();
        $ads = Ad::all();

        return view('shop.home', [
            'categories' => $categories,
            'ads' => $ads,
        ]);

    }
}
