@extends('layouts.app', ['page_name' => '員工後台', 'show_footer' => false, 'show_header' => false])

@section('content')
    <!-- 主容器 -->
    <div class="flex min-h-screen font-sans bg-gray-50">
        <!-- 側邊導航列 -->
        <aside class="hidden w-64 min-h-screen p-4 text-white bg-gray-800 shadow-lg md:block">
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight">讓兄弟組</h2>
            </div>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.dashboard' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                            </svg>
                            網站狀態
                        </a>
                    </li>
                    @can('manage-employees')
                        <li>
                            <a href="{{ route('admin.employees') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.employees' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                                </svg>
                                員工管理
                            </a>
                        </li>
                    @endcan
                    @can('manage-level-2')
                        <li>
                            <a href="{{ route('admin.ads') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.ads' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-9 14H7V7h3v10zm5 0h-3V7h3v10z" />
                                </svg>
                                廣告管理
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.products') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.products' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 18H6V6h2v12zm4 0h-2V6h2v12zm4 0h-2V6h2v12z" />
                                </svg>
                                上架產品
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.inventory') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.inventory' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 16H6V6h12v12z" />
                                </svg>
                                庫存管理
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a href="{{ route('admin.inventory.search') }}"
                            class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.inventory.search' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0017 9.5 6.5 6.5 0 109.5 17c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                            </svg>
                            庫存查詢
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center p-3 text-gray-300 transition-colors rounded-lg hover:bg-gray-700 hover:text-white">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z" />
                            </svg>
                            登出
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- 主要內容 -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- 頂部導航（手機端） -->
            <div class="flex items-center justify-between p-4 bg-white shadow-sm md:hidden">
                <h2 class="text-xl font-bold text-gray-800">員工後台</h2>
                <button id="toggleSidebar" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z" />
                    </svg>
                </button>
            </div>

            <!-- 內容區域 -->
            <div class="mt-6">
                @if (Route::currentRouteName() == 'admin.dashboard')
                    @include('admin.status.index')
                @endif

                <!-- 員工管理 -->
                @can('manage-employees')
                    @if (Route::currentRouteName() == 'admin.employees')
                        @include('admin.employees.index')
                    @endif
                @endcan

                <!-- 其他頁面佔位符 -->
                @if (Route::currentRouteName() == 'admin.ads')
                    @include('admin.ads.index')
                @endif
                @if (Route::currentRouteName() == 'admin.products')
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">上架產品</h3>
                        <p class="text-gray-600">上架產品功能待實現...</p>
                    </div>
                @endif
                @if (Route::currentRouteName() == 'admin.inventory')
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">庫存管理</h3>
                        <p class="text-gray-600">庫存管理功能待實現...</p>
                    </div>
                @endif
                @if (Route::currentRouteName() == 'admin.inventory.search')
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">庫存查詢</h3>
                        <p class="text-gray-600">庫存查詢功能待實現...</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 手機端側邊欄 -->
        <div id="mobileSidebar"
            class="fixed inset-0 z-50 w-64 p-4 text-white transition-transform transform -translate-x-full bg-gray-800 md:hidden">
            <button id="closeSidebar" class="mb-6 text-white focus:outline-none">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <nav>
                <ul class="space-y-2">
                    <li><a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.dashboard' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">網站狀態</a>
                    </li>
                    @can('manage-employees')
                        <li><a href="{{ route('admin.employees') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.employees' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">員工管理</a>
                        </li>
                    @endcan
                    @can('manage-level-2')
                        <li><a href="{{ route('admin.ads') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.ads' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">廣告管理</a>
                        </li>
                        <li><a href="{{ route('admin.products') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.products' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">上架產品</a>
                        </li>
                        <li><a href="{{ route('admin.inventory') }}"
                                class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.inventory' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">庫存管理</a>
                        </li>
                    @endcan
                    <li><a href="{{ route('admin.inventory.search') }}"
                            class="flex items-center p-3 rounded-lg {{ Route::currentRouteName() == 'admin.inventory.search' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">庫存查詢</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center p-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">登出</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- JavaScript 控制手機端側邊欄和搜尋功能 -->
    <script>
        // 手機端側邊欄切換
        document.getElementById('toggleSidebar').addEventListener('click', () => {
            document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');
        });
        document.getElementById('closeSidebar').addEventListener('click', () => {
            document.getElementById('mobileSidebar').classList.add('-translate-x-full');
        });
    </script>
@endsection
