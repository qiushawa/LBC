<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page_name ?? 'LetHimCook' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100">
    @if ($show_header ?? true)
        <header class="bg-white shadow">
            <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900">{{ $page_name ?? 'LetHimCook' }}</h1>
            </div>
        </header>
    @endif

    <div class="flex min-h-screen">
        <!-- 側邊欄 -->
        <aside class="hidden w-64 min-h-screen p-4 text-white bg-gray-800 md:block">
            <div class="mb-6">
                <h2 class="text-2xl font-bold">讓兄弟組</h2>
            </div>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center p-2 rounded-md {{ Route::is('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            網站狀態
                        </a>
                    </li>
                    @can('manage-employees')
                        <li>
                            <a href="{{ route('employees.index') }}"
                               class="flex items-center p-2 rounded-md {{ Route::is('employees.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                員工管理
                            </a>
                        </li>
                    @endcan
                    @can('manage-ads')
                        <li>
                            <a href="{{ route('ads.index') }}"
                               class="flex items-center p-2 rounded-md {{ Route::is('ads.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                                廣告管理
                            </a>
                        </li>
                    @endcan
                    @can('manage-products')
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="flex items-center p-2 rounded-md {{ Route::is('products.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0021.25 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                上架產品
                            </a>
                        </li>
                    @endcan
                    @can('manage-inventory')
                        <li>
                            <a href="{{ route('inventory.index') }}"
                               class="flex items-center p-2 rounded-md {{ Route::is('inventory.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-3V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM9 4h6v2H9V4zm11 15H4V8h16v11z"/></svg>
                                庫存管理
                            </a>
                        </li>
                    @endcan
                    @can('search-inventory')
                        <li>
                            <a href="{{ route('inventory.search') }}"
                               class="flex items-center p-2 rounded-md {{ Route::is('inventory.search') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                                庫存查詢
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="flex items-center p-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
                            登出
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- 手機端側邊欄 -->
        <div id="mobileSidebar" class="fixed inset-0 z-50 w-64 p-4 text-white transition-transform transform -translate-x-full bg-gray-800 md:hidden">
            <button id="closeSidebar" class="mb-6 text-white focus:outline-none">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <nav>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-md {{ Route::is('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">網站狀態</a></li>
                    @can('manage-employees')
                        <li><a href="{{ route('employees.index') }}" class="flex items-center p-2 rounded-md {{ Route::is('employees.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">員工管理</a></li>
                    @endcan
                    @can('manage-ads')
                        <li><a href="{{ route('ads.index') }}" class="flex items-center p-2 rounded-md {{ Route::is('ads.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">廣告管理</a></li>
                    @endcan
                    @can('manage-products')
                        <li><a href="{{ route('products.index') }}" class="flex items-center p-2 rounded-md {{ Route::is('products.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">上架產品</a></li>
                    @endcan
                    @can('manage-inventory')
                        <li><a href="{{ route('inventory.index') }}" class="flex items-center p-2 rounded-md {{ Route::is('inventory.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">庫存管理</a></li>
                    @endcan
                    @can('search-inventory')
                        <li><a href="{{ route('inventory.search') }}" class="flex items-center p-2 rounded-md {{ Route::is('inventory.search') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">庫存查詢</a></li>
                    @endcan
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                           class="flex items-center p-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">登出</a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- 主內容 -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- 手機端頂部導航 -->
            <div class="flex items-center justify-between p-4 bg-white shadow-sm md:hidden">
                <h2 class="text-xl font-bold">員工後台</h2>
                <button id="toggleSidebar" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
                </button>
            </div>

            <!-- 員工帳號顯示 -->
            <div class="p-6 mt-6 bg-white rounded-lg shadow-md">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">員工帳號</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <p class="text-gray-600"><span class="font-medium">姓名：</span>{{ auth()->user()->employee_name }}</p>
                    <p class="text-gray-600"><span class="font-medium">電子郵件：</span>{{ auth()->user()->employee_email }}</p>
                    <p class="text-gray-600"><span class="font-medium">職務：</span>{{ auth()->user()->permission->job_title }}</p>
                    <p class="text-gray-600"><span class="font-medium">權限層級：</span>{{ auth()->user()->permission->permission_level }}</p>
                </div>
            </div>

            <!-- 主內容區域 -->
            <div class="mt-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @if ($show_footer ?? true)
        <footer class="p-4 bg-white shadow">
            <div class="mx-auto text-center text-gray-600 max-w-7xl">
                &copy; {{ date('Y') }} LetHimCook. All rights reserved.
            </div>
        </footer>
    @endif

    <!-- JavaScript 控制手機端側邊欄 -->
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', () => {
            document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');
        });
        document.getElementById('closeSidebar').addEventListener('click', () => {
            document.getElementById('mobileSidebar').classList.add('-translate-x-full');
        });
    </script>
</body>
</html>
