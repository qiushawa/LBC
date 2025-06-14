<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>訂單詳情 - 讓兄弟組</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1e3a8a;
            --secondary: #111827;
            --accent: #2563eb;
            --success: #10b981;
            --error: #ef4444;
        }
        .header-gradient { background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); }
        .sidebar-link { transition: background-color 0.3s ease, color 0.3s ease; }
        .sidebar-link:hover { background-color: rgba(37, 99, 235, 0.1); color: var(--accent); }
        .order-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .order-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="antialiased text-gray-900 bg-gray-100 font-figtree">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <x-header title="讓兄弟組 - 打造你的夢想電腦" />

        <!-- Main Content -->
        <main class="flex-1 px-6 py-8 mx-auto max-w-7xl">
            <div class="flex flex-col gap-8 lg:flex-row">
                <!-- Sidebar -->
                <aside class="p-6 bg-white shadow-sm lg:w-1/4 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">帳戶管理</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 font-medium text-gray-700 rounded-lg sidebar-link hover:bg-accent/10 hover:text-accent">個人資料</a>
                        <a href="{{ route('user.orders') }}" class="block px-4 py-2 font-medium text-gray-700 rounded-lg sidebar-link bg-accent/10 text-accent">訂單紀錄</a>
                        <a href="{{ route('user.settings') }}" class="block px-4 py-2 font-medium text-gray-700 rounded-lg sidebar-link hover:bg-accent/10 hover:text-accent">帳戶設定</a>
                    </nav>
                </aside>

                <!-- Order Details Content -->
                <div class="lg:w-3/4">
                    <div class="p-8 transition-all bg-white shadow-sm order-card rounded-xl hover:shadow-md">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">訂單詳情 #{{ $order->order_id }}</h2>
                            <span class="text-sm font-medium {{ $order->order_status == 'completed' ? 'text-success' : ($order->order_status == 'canceled' ? 'text-error' : 'text-gray-600') }}">
                                {{ __('order_status.' . $order->order_status) }}
                            </span>
                        </div>

                        <!-- Order Information -->
                        <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700">訂單日期</p>
                                <p class="text-gray-600">{{ $order->order_date->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">付款狀態</p>
                                <p class="text-gray-600">{{ __('payment_status.' . $order->payment_status) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">收貨人</p>
                                <p class="text-gray-600">{{ $order->recipient_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">聯絡電話</p>
                                <p class="text-gray-600">{{ $order->recipient_phone }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm font-medium text-gray-700">收貨地址</p>
                                <p class="text-gray-600">{{ $order->shipping_address }}</p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">訂購項目</h3>
                        <div class="border-t border-gray-200">
                            @foreach ($order->orderDetails as $detail)
                                <div class="flex justify-between py-4 border-b border-gray-200">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ $detail->product->product_name }}</p>
                                        <p class="text-sm text-gray-600">數量：{{ $detail->quantity }}</p>
                                        @if ($detail->discount)
                                            <p class="text-sm text-success">折扣：{{ $detail->discount->discount_name }} (NT${{ $detail->discount_amount }})</p>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">NT${{ number_format($detail->subtotal) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Assembly Service -->
                        @if ($order->assemblyService)
                            <div class="py-4 border-b border-gray-200">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">組裝服務：{{ $order->assemblyService->service_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->assemblyService->service_description }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">NT${{ number_format($order->assemblyService->service_fee) }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="py-4">
                            <div class="flex justify-between text-sm">
                                <p class="text-gray-700">運費</p>
                                <p class="font-medium text-gray-900">NT${{ number_format($order->shipping_fee) }}</p>
                            </div>
                            <div class="flex justify-between mt-2 text-lg font-semibold">
                                <p class="text-gray-900">總金額</p>
                                <p class="text-gray-900">NT${{ number_format($order->total_amount) }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('user.orders') }}" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg btn hover:bg-gray-300">返回訂單列表</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-white bg-secondary">
            <div class="px-6 mx-auto max-w-7xl">
                <div class="flex flex-col items-center justify-between sm:flex-row">
                    <p>© 2025 讓兄弟組. All rights reserved.</p>
                    <div class="flex mt-4 space-x-4 sm:mt-0">
                        <a href="#" class="hover:text-gray-300">關於我們</a>
                        <a href="#" class="hover:text-gray-300">聯繫我們</a>
                        <a href="#" class="hover:text-gray-300">隱私政策</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
