<?php

namespace App\Http\Controllers\Shop;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\ProductCategorie;
use App\Models\Ad;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategorie::all();
        $ads = Ad::all();

        return view('shop.home', [
            'categories' => $categories,
            'ads' => $ads,
        ]);

    }
    public function showAd($id)
    {
        $ad = Ad::findOrFail($id);
        return view('shop.ad', [
            'ad' => $ad,
            'content_html' => (new \League\CommonMark\CommonMarkConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false
            ]))->convert($ad->ad_content)->getContent(),
        ]);
    }

    public function showLogin()
    {
        return view('shop.auth.login');
    }
    public function login(Request $request)
    {

        return redirect()->route('shop.index');
    }
    public function logout(Request $request)
    {
        // 處理登出邏輯
        // ...
        return redirect()->route('shop.index');
    }
    public function showRegister()
    {
        return view('shop.register');
    }
    public function register(Request $request)
    {
        // 處理註冊邏輯
        // ...
        return redirect()->route('shop.index');
    }
}
