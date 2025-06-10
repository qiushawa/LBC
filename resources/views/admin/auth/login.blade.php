@extends('layouts.app', ['page_name' => '員工登入', 'show_footer' => false, 'show_header' => false])

@section('content')
    <div class="flex items-center justify-center min-h-screen p-4 bg-gray-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">員工登入</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="employee_email" class="block text-sm font-medium text-gray-700">電子郵件</label>
                    <input type="email" name="employee_email" id="employee_email" value="{{ old('employee_email') }}"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('employee_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">密碼</label>
                    <input type="password" name="password" id="password"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="w-full px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">登入</button>
                </div>
            </form>
        </div>
    </div>
@endsection
