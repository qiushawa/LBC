@extends('layouts.app', ['page_name' => '新增員工', 'show_footer' => false, 'show_header' => false])

@section('content')
    <div class="min-h-screen p-4 bg-gray-50 sm:p-6 lg:p-8">
        <div class="max-w-lg p-6 mx-auto bg-white rounded-lg shadow-md">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">新增員工</h3>
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="employee_name" class="block text-sm font-medium text-gray-700">姓名</label>
                    <input type="text" name="employee_name" id="employee_name" value="{{ old('employee_name') }}"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('employee_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="employee_email" class="block text-sm font-medium text-gray-700">Email</label>
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
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">確認密碼</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="permission_id" class="block text-sm font-medium text-gray-700">權限等級</label>
                    <select name="permission_id" id="permission_id"
                            class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->permission_id }}" {{ old('permission_id') == $permission->permission_id ? 'selected' : '' }}>
                                {{ $permission->job_title }} (等級 {{ $permission->permission_level }})
                            </option>
                        @endforeach
                    </select>
                    @error('permission_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.employees') }}"
                       class="px-4 py-2 text-gray-600 hover:text-gray-800">取消</a>
                    <button type="submit"
                            class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">新增</button>
                </div>
            </form>
        </div>
    </div>
@endsection
