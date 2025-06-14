<!-- resources/views/settings.blade.php -->
@extends('layouts.dashboard')

@section('title', '帳戶設定')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <form method="POST" action="{{ route('user.settings.update') }}">
            @csrf
            <div class="mb-4">
                <label for="preferred_language" class="block font-medium text-gray-700">偏好語言</label>
                <select name="preferred_language" id="preferred_language"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="zh-TW" {{ old('preferred_language', auth()->user()->preferred_language) == 'zh-TW' ? 'selected' : '' }}>
                        繁體中文
                    </option>
                    <option value="en-US" {{ old('preferred_language', auth()->user()->preferred_language) == 'en-US' ? 'selected' : '' }}>
                        English
                    </option>
                    <option value="ja-JP" {{ old('preferred_language', auth()->user()->preferred_language) == 'ja-JP' ? 'selected' : '' }}>
                        日本語
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="theme" class="block font-medium text-gray-700">佈景主題</label>
                <select name="theme" id="theme"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="light" {{ old('theme', auth()->user()->theme) == 'light' ? 'selected' : '' }}>
                        亮色主題
                    </option>
                    <option value="dark" {{ old('theme', auth()->user()->theme) == 'dark' ? 'selected' : '' }}>
                        暗色主題
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="display_currency" class="block font-medium text-gray-700">顯示貨幣</label>
                <select name="display_currency" id="display_currency"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="TWD" {{ old('display_currency', auth()->user()->display_currency) == 'TWD' ? 'selected' : '' }}>
                        新台幣 (TWD)
                    </option>
                    <option value="USD" {{ old('display_currency', auth()->user()->display_currency) == 'USD' ? 'selected' : '' }}>
                        美元 (USD)
                    </option>
                    <option value="JPY" {{ old('display_currency', auth()->user()->display_currency) == 'JPY' ? 'selected' : '' }}>
                        日元 (JPY)
                    </option>
                </select>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('shop.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">返回</a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    儲存設定
                </button>
            </div>
        </form>
    </div>
@endsection
