<!-- 總商品數 -->
<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
    <div class="p-6 transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800">總庫存記錄數</h3>
        <span class="text-3xl font-bold text-blue-600">{{ $totalProducts }}</span>/件
    </div>
</div>

<!-- 搜尋欄與類別篩選 -->
<div class="mb-8">
    <!-- 搜尋表單 -->
    <form method="GET" action="{{ route('admin.inventory.search') }}" class="mb-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="flex-1">
                <input type="text" name="search" id="search" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="輸入商品名稱或庫存編號" value="{{ request()->query('search') }}">
            </div>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">搜尋</button>
        </div>
    </form>

    <!-- 類別篩選 -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.inventory.search') }}?search={{ request()->query('search') }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 {{ !request()->query('category_id') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
            全部
        </a>
        @foreach ($categorys as $category)
            <a href="{{ route('admin.inventory.search') }}?category_id={{ $category->category_id }}&search={{ request()->query('search') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 {{ request()->query('category_id') == $category->category_id ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                {{ $category->category_name }}
            </a>
        @endforeach
    </div>
</div>

<!-- 庫存查詢表格（僅在有搜尋結果時顯示） -->
@if (request()->query('search') || request()->query('category_id'))
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-semibold text-gray-800">庫存查詢</h3>
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
                    <th class="p-3 text-sm font-medium text-gray-700">庫存狀態</th>
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
                        <td class="p-3 text-sm {{ $product->stock_status === 'normal' ? 'text-green-600' : ($product->stock_status === 'low_stock' ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $product->stock_status === 'discontinued' ? '停售' : ($product->stock_status === 'out_of_stock' ? '缺貨' : ($product->stock_status === 'low_stock' ? '低庫存' : '正常')) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-sm text-center text-gray-500">沒有找到符合條件的庫存記錄</td>
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
@endif
