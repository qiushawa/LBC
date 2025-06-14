<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    // Ensure the user is authenticated

    // Display the user profile page
    public function show()
    {
        $user = Auth::guard()->user();
        // Ensure $user is an instance of App\Models\User (Eloquent model)
        if (!($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }
        return view('user.profile', compact('user'));
    }

    // Update the user profile
    public function update(Request $request)
    {
        $user = Auth::user();
        // Ensure $user is an instance of App\Models\User (Eloquent model)
        if (!($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', '個人資料已成功更新！');
    }
}
