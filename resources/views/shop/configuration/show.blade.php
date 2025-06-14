<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>配置確認 - 讓兄弟組</title>
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

        .select-container select {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .select-container select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .action-button {
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .checkbox-container input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50 font-figtree">
    <div class="container max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <nav class="mb-8">
            <ol class="flex items-center justify-center space-x-4 text-sm font-medium">
                <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step">
                    <span>1. 選擇商品</span>
                </li>
                <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step active">
                    <span>2. 確認配置</span>
                </li>
                <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step">
                    <span>3. 完成訂單</span>
                </li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="overflow-hidden bg-white shadow-lg rounded-xl">
            <div class="p-6 sm:p-8">
                <h1 class="mb-2 text-2xl font-bold text-gray-900 sm:text-3xl">配置確認</h1>
                <p class="mb-6 text-gray-600">請確認您的商品選擇並選擇適用的折扣。</p>

                <h2 class="mb-4 text-xl font-semibold text-gray-800">配置名稱：{{ $configuration->config_name }}</h2>

                <form action="{{ route('configuration.updateDiscounts', $configuration->config_id) }}" method="POST" id="discount-form">
                    @csrf
                    @method('PATCH')

                    <!-- Product Details Table -->
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
                                            <span class="font-medium">{{ $detail->product->product_name ?? '未知商品' }}</span>
                                            <p class="text-sm text-gray-500">數量：{{ $detail->quantity }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-gray-700">${{ number_format($detail->unit_price, 2) }}</td>
                                        <td class="px-4 py-4">
                                            <div class="select-container">
                                                <select name="discounts[{{ $detail->detail_id }}]" id="discount_{{ $detail->detail_id }}" class="w-full p-2 text-gray-700 border rounded-md focus:outline-none" onchange="updateSubtotal(this, {{ $detail->detail_id }})">
                                                    <option value="0" {{ is_null($detail->discount_id) ? 'selected' : '' }}>無折扣</option>
                                                    @foreach ($discounts->where('product_id', $detail->product_id) as $discount)
                                                        @if (now()->between($discount['start_date'], $discount['end_date']))
                                                            <option value="{{ $discount['discount_id'] }}" {{ $detail->discount_id == $discount['discount_id'] ? 'selected' : '' }}>
                                                                {{ $discount['discount_name'] }} ({{ $discount['discount_type'] == 'percentage' ? $discount['discount_value'] . '%' : 'NT$' . $discount['discount_value'] }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-gray-700">
                                            <span id="subtotal_{{ $detail->detail_id }}">${{ number_format(($detail->unit_price - $detail->discount_amount) * $detail->quantity, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Assembly Service Checkbox -->
                    <div class="mt-6">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="assembly_service" class="w-5 h-5 border-gray-300 rounded checkbox-container text-accent focus:ring-accent" {{ $configuration->service_id ? 'checked' : '' }}>
                            <span class="text-gray-700">是否需要組裝服務</span>
                        </label>
                    </div>

                    <!-- Total Price -->
                    <div class="flex justify-end mt-6">
                        <p class="text-lg font-semibold text-gray-900">
                            總價格：<span id="total-price" class="text-accent">${{ number_format($configuration->total_price, 2) }}</span>
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('shop.index') }}" class="px-6 py-3 text-white bg-gray-500 rounded-lg action-button hover:bg-gray-600 focus:outline-none">
                            返回首頁
                        </a>
                        <button type="submit" class="px-6 py-3 text-white bg-blue-700 rounded-lg action-button focus:outline-none">
                            確認訂單
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateSubtotal(select, detailId) {
            const discountId = parseInt(select.value);
            const unitPrice = parseFloat(@json($configuration->details->pluck('unit_price', 'detail_id'))[detailId]);
            const quantity = parseInt(@json($configuration->details->pluck('quantity', 'detail_id'))[detailId]);

            let discountAmount = 0;
            if (discountId > 0) {
                const discount = @json($discounts->keyBy('discount_id'))[discountId];
                if (discount) {
                    discountAmount = discount.discount_type === 'percentage'
                        ? (unitPrice * discount.discount_value) / 100
                        : discount.discount_value;
                }
            }

            const subtotal = (unitPrice - discountAmount) * quantity;
            document.getElementById(`subtotal_${detailId}`).textContent = `$${subtotal.toFixed(2)}`;

            // Update total price
            let totalPrice = 0;
            const discountElements = document.querySelectorAll('select[name^="discounts["]');
            discountElements.forEach(element => {
                const detailId = element.id.replace('discount_', '');
                const currentDiscountId = parseInt(element.value);
                const currentUnitPrice = parseFloat(@json($configuration->details->pluck('unit_price', 'detail_id'))[detailId]);
                const currentQuantity = parseInt(@json($configuration->details->pluck('quantity', 'detail_id'))[detailId]);
                let currentDiscountAmount = 0;
                if (currentDiscountId > 0) {
                    const discount = @json($discounts->keyBy('discount_id'))[currentDiscountId];
                    if (discount) {
                        currentDiscountAmount = discount.discount_type === 'percentage'
                            ? (currentUnitPrice * discount.discount_value) / 100
                            : discount.discount_value;
                    }
                }
                totalPrice += (currentUnitPrice - currentDiscountAmount) * currentQuantity;
            });

            // Add assembly service fee if checked
            const assemblyCheckbox = document.querySelector('input[name="assembly_service"]');
            if (assemblyCheckbox.checked) {
                totalPrice += {{ $assemblyService ? $assemblyService->service_fee : 0 }}; // Default service fee
            }

            document.getElementById('total-price').textContent = `$${totalPrice.toFixed(2)}`;
        }

        // Handle AJAX form submission
        document.getElementById('discount-form').addEventListener('submit', async function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const discounts = {};
            let assemblyService = false;
            formData.forEach((value, key) => {
                if (key.startsWith('discounts[')) {
                    const detailId = key.match(/\d+/)[0];
                    discounts[detailId] = value === '0' ? null : parseInt(value);
                } else if (key === 'assembly_service') {
                    assemblyService = true;
                }
            });

            try {
                const response = await fetch(this.action, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ discounts, assembly_service: assemblyService })
                });

                const result = await response.json();

                if (response.ok) {
                    window.location.href = result.redirect; // Redirect to order confirmation
                } else {
                    alert('更新失敗：' + result.message);
                }
            } catch (error) {
                console.error('提交錯誤：', error);
                alert('發生錯誤，請稍後重試。');
            }
        });
    </script>
</body>
</html>
