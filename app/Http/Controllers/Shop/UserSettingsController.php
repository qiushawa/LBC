<?php

namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UserSetting;

class UserSettingsController extends Controller
{
    // Ensure the user is authenticated


    // Display the user settings page
    public function index()
    {
        $user = Auth::user();
        $settings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_language' => 'zh-TW', // Default language
                'theme' => 'light', // Default theme
                'display_currency' => 'TWD', // Default currency
            ]
        );

        return view('user.settings', compact('settings'));
    }

    // Update user settings
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = UserSetting::where('user_id', $user->id)->firstOrFail();

        // Validation rules
        $validator = Validator::make($request->all(), [
            'preferred_language' => ['required', 'string', 'max:10', 'in:zh-TW,en-US,ja-JP'], // Example language options
            'theme' => ['required', 'in:dark,light'],
            'display_currency' => ['required', 'string', 'max:10', 'in:TWD,USD,JPY'], // Example currency options
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update settings
        $settings->update([
            'preferred_language' => $request->input('preferred_language'),
            'theme' => $request->input('theme'),
            'display_currency' => $request->input('display_currency'),
        ]);

        return redirect()->route('user.settings')->with('success', '設定已成功更新！');
    }
}
