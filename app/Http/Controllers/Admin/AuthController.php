<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 顯示登入表單
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * 處理登入請求
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'employee_email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('employee')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', '登入成功');
        }

        return back()->withErrors([
            'employee_email' => '電子郵件或密碼錯誤',
        ])->onlyInput('employee_email');
    }

    /**
     * 處理登出
     */
    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', '已登出');
    }
}
