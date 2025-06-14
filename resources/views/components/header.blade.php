@props(['title' => '讓兄弟組'])

<header {{ $attributes->merge(['class' => 'bg-gray-800 text-white shadow-lg sticky top-0 z-50']) }}>
    <div class="flex items-center justify-between px-6 py-4 mx-auto max-w-7xl">
        <!-- Logo -->
        <a href="/" class="text-2xl font-bold tracking-tight">{{ $title }}</a>

        <!-- Navigation -->
        <div class="flex items-center space-x-6">
            @auth
                <div x-data="{ open: false }" class="relative">
                    <button
                        x-on:click="open = !open"
                        class="flex items-center space-x-2 text-white hover:text-gray-300 focus:outline-none"
                        aria-label="用戶選單"
                    >
                        <span class="text-sm font-medium">歡迎，{{ auth()->user()->name }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-on:click.away="open = false"
                        x-transition
                        class="absolute right-0 z-50 w-48 mt-2 bg-white shadow-xl rounded-xl"
                    >
                        <div class="py-2">
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-t-xl">個人資料</a>
                            <a href="{{ route('user.orders') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">訂單紀錄</a>
                            <a href="{{ route('user.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">帳戶設定</a>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-gray-700 hover:bg-gray-50 rounded-b-xl">登出</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('user.login') }}" class="font-medium text-white hover:text-gray-300">登入</a>
                <a href="{{ route('user.register') }}" class="px-4 py-2 font-medium text-gray-800 transition bg-white rounded-full hover:bg-gray-100">註冊</a>
            @endauth
        </div>
    </div>
</header>
