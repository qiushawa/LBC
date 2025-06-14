<!-- resources/views/profile.blade.php -->
@extends('layouts.dashboard')

@section('title', '個人資料')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">

        <form method="POST" action="{{ route('user.profile.update') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">姓名</label>
                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-medium text-gray-700">電子郵件</label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block font-medium text-gray-700">新密碼（可選）</label>
                <input type="password" name="password" id="password"
                       class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block font-medium text-gray-700">確認新密碼</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('shop.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">取消</a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    更新資料
                </button>
            </div>
        </form>
    </div>
@endsection
