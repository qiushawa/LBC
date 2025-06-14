<!-- resources/views/layouts/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>控制面版 - 讓兄弟組</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md">
            <div class="p-6">
                <a href="/" class="text-2xl font-bold tracking-tight">讓兄弟組</a>
            </div>
            <nav class="mt-6">
                <ul>
                    <li>
                        <a href="{{ route('user.profile') }}"
                           class="block px-6 py-3 text-gray-600 hover:bg-blue-100 hover:text-blue-600 {{ request()->routeIs('user.profile') ? 'bg-blue-100 text-blue-600' : '' }}">
                            個人資料
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.orders') }}"
                           class="block px-6 py-3 text-gray-600 hover:bg-blue-100 hover:text-blue-600 {{ request()->routeIs('user.orders') ? 'bg-blue-100 text-blue-600' : '' }}">
                            訂單紀錄
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.settings') }}"
                           class="block px-6 py-3 text-gray-600 hover:bg-blue-100 hover:text-blue-600 {{ request()->routeIs('user.settings') ? 'bg-blue-100 text-blue-600' : '' }}">
                            帳戶設定
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <header class="flex items-center justify-between p-4 bg-white shadow">
                <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
                <div>
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                    <a href="{{ route('user.logout') }}"
                       class="ml-4 text-blue-600 hover:text-blue-800">登出</a>
                </div>
            </header>
            <!-- Content Area -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="p-4 text-center text-gray-600 bg-white shadow">
                <p>© 2025 讓兄弟組. All rights reserved.</p>
                <div class="mt-2">
                    <a href="#" class="text-blue-600 hover:underline">關於我們</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="text-blue-600 hover:underline">聯繫我們</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="text-blue-600 hover:underline">隱私政策</a>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
