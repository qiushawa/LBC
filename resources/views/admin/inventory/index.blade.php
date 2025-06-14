<!-- 保持原有的總商品數和類別篩選部分不變 -->
<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
    <div class="p-6 transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800">總商品數</h3>
        <span class="text-3xl font-bold text-blue-600">{{ $totalProducts }}</span>/件
    </div>
</div>
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.inventory') }}"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 {{ !request()->query('category_id') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
        全部
    </a>
    @foreach ($categorys as $category)
        <a href="{{ route('admin.inventory') }}?category_id={{ $category->category_id }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 {{ request()->query('category_id') == $category->category_id ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
            {{ $category->category_name }}
        </a>
    @endforeach
</div>

<!-- 庫存管理表格 -->
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-semibold text-gray-800">庫存管理</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-sm font-medium text-gray-700">庫存編號</th>
                    <th class="p-3 text-sm font-medium text-gray-700">商品名稱</th>
                    <th class="p-3 text-sm font-medium text-gray-700">類別</th>
                    <th class="p-3 text-sm font-medium text-gray-700">供應商</th>
                    <th class="p-3 text-sm font-medium text-gray-700">價格</th>
                    <th class="p-3 text-sm font-medium text-gray-700">庫存數量</th>
                    <th class="p-3 text-sm font-medium text-gray-700">低庫存門檻</th>
                    <th class="p-3 text-sm font-medium text-gray-700">庫存狀態</th>
                    <th class="p-3 text-sm font-medium text-gray-700">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm text-gray-600">{{ $product->inventory_id }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->product_name }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->category_name }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->supplier_name }}</td>
                        <td class="p-3 text-sm text-gray-600">${{ number_format($product->product_price, 2) }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->stock_quantity }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->low_stock_threshold }}</td>
                        <td class="p-3 text-sm {{ $product->stock_status === 'normal' ? 'text-green-600' : ($product->stock_status === 'low_stock' ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $product->stock_status === 'discontinued' ? '停售' : ($product->stock_status === 'out_of_stock' ? '缺貨' : ($product->stock_status === 'low_stock' ? '低庫存' : '正常')) }}
                        </td>
                        <td class="p-3 text-sm">
                            <button onclick="openModal('{{ $product->inventory_id }}', '{{ $product->stock_quantity }}', '{{ $product->low_stock_threshold }}')"
                                class="px-3 py-1 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                編輯
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-4 text-sm text-center text-gray-500">沒有找到符合條件的庫存記錄</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- 分頁 -->
    <div class="mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>

<!-- 彈出視窗 -->
<div id="inventoryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h3 class="mb-4 text-lg font-semibold text-gray-800">更新庫存</h3>
        <form id="inventoryForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="stock_adjustment" class="block text-sm font-medium text-gray-700">庫存調整數量</label>
                <input type="number" name="stock_adjustment" id="stock_adjustment" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="輸入調整數量（正數增加，負數減少）" required>
                @error('stock_adjustment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">低庫存門檻</label>
                <input type="number" name="low_stock_threshold" id="low_stock_threshold" min="0" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="輸入低庫存門檻" required>
                @error('low_stock_threshold')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">取消</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">提交</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript 控制彈出視窗 -->
<script>
    function openModal(inventoryId, stockQuantity, lowStockThreshold) {
        const modal = document.getElementById('inventoryModal');
        const form = document.getElementById('inventoryForm');
        const stockInput = document.getElementById('stock_adjustment');
        const thresholdInput = document.getElementById('low_stock_threshold');

        // 動態設置表單 action，確保 HTTPS
        let baseUrl = `{{ route('admin.inventory.update', ':inventory_id') }}`.replace(':inventory_id', inventoryId);
        if (baseUrl.startsWith('http://')) {
            baseUrl = baseUrl.replace('http://', 'https://');
        }
        form.action = baseUrl;

        // 設置輸入欄
        stockInput.value = 0;
        stockInput.min = -parseInt(stockQuantity);
        thresholdInput.value = lowStockThreshold;

        // 顯示 modal
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('inventoryModal').classList.add('hidden');
    }

    // 點擊遮罩關閉
    document.getElementById('inventoryModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
