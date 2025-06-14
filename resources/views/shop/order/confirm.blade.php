<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>訂單確認 - 讓兄弟組</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #1f2937;
            --accent: #3b82f6;
        }

        .progress-step {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .progress-step.active {
            background-color: var(--accent);
            color: white;
        }

        .table-row:hover {
            background-color: #f9fafb;
        }

        .action-button {
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .input-field {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-field:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>

<body class="antialiased text-gray-800 bg-gray-50 font-figtree">
    <div class="container max-w-5xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <nav class="mb-8">
            <ol class="flex items-center justify-center space-x-4 text-sm font-medium">
                <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step">
                    <span>1. 選擇商品</span>
                </li>
                <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step">
                    <span>2. 確認配置</span>
                </li>
                <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step active">
                    <span>3. 完成訂單</span>
                </li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="overflow-hidden bg-white shadow-lg rounded-xl">
            <div class="p-6 sm:p-8">
                <h1 class="mb-2 text-2xl font-bold text-gray-900 sm:text-3xl">訂單確認</h1>
                <p class="mb-6 text-gray-600">請填寫收貨資訊並審核訂單詳情，確認後提交訂單。</p>

                <div class="flex flex-col gap-8 lg:flex-row">
                    <!-- Left: User Information Form -->
                    <div class="lg:w-1/2">
                        <h2 class="mb-4 text-xl font-semibold text-gray-800">收貨資訊</h2>
                        <form action="{{ route('order.submit', $configuration->config_id) }}" method="POST"
                            id="order-submit-form">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="recipient_name"
                                        class="block text-sm font-medium text-gray-700">收貨人姓名</label>
                                    <input type="text" name="recipient_name" id="recipient_name"
                                        class="block w-full p-2 mt-1 text-gray-700 border rounded-md input-field focus:outline-none"
                                        required>
                                </div>
                                <div>
                                    <label for="recipient_phone"
                                        class="block text-sm font-medium text-gray-700">收貨人電話</label>
                                    <input type="text" name="recipient_phone" id="recipient_phone"
                                        class="block w-full p-2 mt-1 text-gray-700 border rounded-md input-field focus:outline-none"
                                        required>
                                </div>
                                <div>
                                    <label for="shipping_address"
                                        class="block text-sm font-medium text-gray-700">收貨地址</label>
                                    <input type="text" name="shipping_address" id="shipping_address"
                                        class="block w-full p-2 mt-1 text-gray-700 border rounded-md input-field focus:outline-none"
                                        required>
                                </div>
                                <div style="display: none;">
                                    <input type="text" name="service_id" id="service_id"
                                        class="block w-full p-2 mt-1 text-gray-700 border rounded-md input-field focus:outline-none"
                                        required value="{{ $configuration->service_id ? $configuration->service_id : '' }}">
                                </div>
                                <div>
                                    <label for="payment_method"
                                        class="block text-sm font-medium text-gray-700">付款方式</label>
                                    <select name="payment_method" id="payment_method"
                                        class="block w-full p-2 mt-1 text-gray-700 border rounded-md input-field focus:outline-none"
                                        required>
                                        <option value="credit_card">信用卡</option>
                                        <option value="cash_on_delivery">貨到付款</option>
                                        <option value="bank_transfer">銀行轉帳</option>
                                        <option value="e_wallet">電子錢包</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button type="submit"
                                    class="px-6 py-3 text-white rounded-lg action-button bg-accent hover:bg-blue-700 focus:outline-none">
                                    提交訂單
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right: Order Details -->
                    <div class="lg:w-1/2">
                        <h2 class="mb-4 text-xl font-semibold text-gray-800">訂單詳情</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="text-sm text-left text-gray-700 uppercase bg-gray-100">
                                        <th class="px-4 py-3 font-semibold">商品</th>
                                        <th class="px-4 py-3 font-semibold">單價</th>
                                        <th class="px-4 py-3 font-semibold">折扣</th>
                                        <th class="px-4 py-3 font-semibold">小計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configuration->details as $detail)
                                        <tr class="table-row border-b border-gray-200">
                                            <td class="px-4 py-4">
                                                <span
                                                    class="font-medium">{{ $detail->product->product_name ?? '未知商品' }}</span>
                                                <p class="text-sm text-gray-500">數量：{{ $detail->quantity }}</p>
                                            </td>
                                            <td class="px-4 py-4 text-gray-700">
                                                ${{ number_format($detail->unit_price, 2) }}</td>
                                            <td class="px-4 py-4 text-gray-700">
                                                @if ($detail->discount_id)
                                                    @php
                                                        $discount = $detail->discount()->first();
                                                    @endphp
                                                    {{ $discount->discount_name }}
                                                    ({{ $discount->discount_type == 'percentage' ? $discount->discount_value . '%' : 'NT$' . $discount->discount_value }})
                                                @else
                                                    無折扣
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-gray-700">
                                                ${{ number_format($detail->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Assembly Service -->
                        <div class="mt-6">
                            <p class="text-gray-700">
                                組裝服務：{{ $configuration->service_id ? $configuration->assemblyService->service_name . ' (NT$' . $configuration->assemblyService->service_fee . ')' : '不需要' }}
                            </p>
                        </div>

                        <!-- Shipping Fee (Example) -->
                        <div class="mt-4">
                            <p class="text-gray-700">
                                運費：NT${{ $shipping_fee ?? 100 }} <!-- Example fee -->
                            </p>
                        </div>

                        <!-- Total Amount -->
                        <div class="flex justify-end mt-4">
                            <p class="text-lg font-semibold text-gray-900">
                                總金額：<span
                                    class="text-accent">${{ number_format($configuration->total_price + ($configuration->service_id ? $configuration->assemblyService->service_fee : 0) + ($shipping_fee ?? 100), 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="flex justify-start mt-8">
                    <a href="{{ route('configuration.show', $configuration->config_id) }}"
                        class="px-6 py-3 text-white bg-gray-500 rounded-lg action-button hover:bg-gray-600 focus:outline-none">
                        返回修改
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
