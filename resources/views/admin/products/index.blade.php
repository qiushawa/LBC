<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
    <div class="p-6 transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800">總商品數</h3>
        <span class="text-3xl font-bold text-blue-600">{{ $totalProducts }}</span>/件
    </div>

</div>
<!-- 添加按鈕組 -->
<div class="flex flex-wrap gap-2 mb-4">
    @foreach ($categorys as $category)
        <a href="{{ route('admin.products') }}?category_id={{ $category->category_id }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 {{ request()->query('category_id') == $category->category_id ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
            {{ $category->category_name }}
        </a>
    @endforeach
</div>
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-semibold text-gray-800">商品列表</h3>
        <form method="GET" action="{{ route('admin.products') }}"
            class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto">

            <div class="flex gap-2">
                <a href="{{ route('admin.products.create') }}"
                    class="px-4 py-2 text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700">新增商品</a>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-sm font-medium text-gray-700">商品編號</th>
                    <th class="p-3 text-sm font-medium text-gray-700">供應商名稱</th>
                    <th class="p-3 text-sm font-medium text-gray-700">商品名稱</th>
                    <th class="p-3 text-sm font-medium text-gray-700">商品價格</th>
                    <th class="p-3 text-sm font-medium text-gray-700">狀態</th>
                    <th class="p-3 text-sm font-medium text-gray-700">操作</th>
                </tr>
            </thead>
            <tbody id="employeeTable">
                @forelse ($products as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm text-gray-600">{{ $product->product_id }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->supplier_name }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $product->product_name }}</td>
                        <td class="p-3 text-sm text-gray-600">${{ $product->product_price }}</td>
                        @if ($product->launch_status == 'active')
                            <td class="p-3 text-sm text-green-600">上架中</td>
                        @else
                            <td class="p-3 text-sm text-red-600">已下架</td>
                        @endif
                        <td class="p-3 text-sm">
                            <a href="{{ route('admin.products.edit', $product->product_id) }}"
                                class="text-blue-600 hover:underline">編輯</a>
                            <form action="{{route("admin.products.delete", $product->product_id)}}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('確定要刪除此商品？');"
                                    class="ml-2 text-red-600 hover:underline">刪除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-sm text-center text-gray-500">沒有找到符合條件的商品
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Links -->
    <div class="mt-4" id="paginationLinks">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
