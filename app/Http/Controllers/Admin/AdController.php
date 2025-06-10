<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdController extends Controller
{
    // Fetch ad data
    public function show($id)
    {
        $ad = Ad::findOrFail($id);
        return response()->json([
            'ad_title' => $ad->ad_title,
            'content' => $ad->ad_content ?? '', // Markdown content field
            'banner_url' => $ad->banner_url
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ad_title' => 'required|string|max:255',
            'content' => 'nullable|string'
        ]);

        $ad = Ad::findOrFail($id);
        $ad->ad_title = $request->ad_title;
        $ad->ad_content = $request->content;
        $ad->save();

        return response()->json(['message' => 'Ad updated successfully'])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
    public function index(): \Illuminate\View\View
    {
        $ads = Ad::all()->map(function ($ad) {
            $ad->ad_title = htmlspecialchars($ad->ad_title, ENT_QUOTES, 'UTF-8');
            $ad->banner_url = $ad->getBannerUrlAttribute();
            $ad->content_html = (new \League\CommonMark\CommonMarkConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false
            ]))->convert($ad->ad_content)->getContent();
            return $ad;
        });
        // 廣告數量
        $totalAds = $ads->count();
        return view('admin.dashboard', compact('ads', 'totalAds'));
    }
    public function uploadBanner(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|exists:ads,ad_id',
            'banner' => 'required|image|mimes:png,jpeg|max:2048',
        ]);

        $ad = Ad::findOrFail($request->ad_id);

        // Determine the filename
        $img = $ad->ad_banner ?? Str::random(10); // Use ad_banner or generate 10-char random hash
        $filename = $img . '.png';
        // Store the file in public/images/ads with the specified filename
        $path = $request->file('banner')->storeAs('images/ads', $filename, 'public');

        // Update ad_banner if it was generated
        if (!$ad->ad_banner) {
            $ad->ad_banner = pathinfo($filename, PATHINFO_FILENAME); // Store only the hash without extension
        }

        // Update banner_url to point to the stored file
        $ad->ad_banner = $img;
        $ad->save();

        return response()->json(['message' => 'Banner uploaded successfully'], 200)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad_title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'banner' => 'required|image|mimes:png,jpeg|max:2048',
        ]);

        $ad = new Ad();
        $ad->ad_title = $request->ad_title;
        $ad->ad_content = $request->content;

        // Generate a random hash for the banner
        $img = Str::random(10);
        $filename = $img . '.png';
        // Store the file in public/images/ads with the specified filename
        $path = $request->file('banner')->storeAs('images/ads', $filename, 'public');

        // Save the ad_banner as the hash without extension
        $ad->ad_banner = pathinfo($filename, PATHINFO_FILENAME);
        $ad->save();

        return response()->json(['message' => 'Ad created successfully'], 201)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();
        // 返回頁面
        return redirect()->route('admin.ads')->with('success', '廣告已刪除');

    }
}
