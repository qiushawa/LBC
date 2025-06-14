<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
    <div class="p-6 transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800">總折價數</h3>
        <span class="text-3xl font-bold text-blue-600">{{ $totalDiscounts }}</span>/項
    </div>
</div>

<div class="p-6 bg-white rounded-lg shadow-md">
    @if (session('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-semibold text-gray-800">折價列表</h3>
        <form method="GET" action="{{ route('admin.discounts') }}" class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto">
            <div class="flex gap-2">
                <button type="button" onclick="openModal('create')"
                    class="px-4 py-2 text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700">新增折價</button>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-sm font-medium text-gray-700">折價編號</th>
                    <th class="p-3 text-sm font-medium text-gray-700">折價名稱</th>
                    <th class="p-3 text-sm font-medium text-gray-700">類型</th>
                    <th class="p-3 text-sm font-medium text-gray-700">數值</th>
                    <th class="p-3 text-sm font-medium text-gray-700">開始日期</th>
                    <th class="p-3 text-sm font-medium text-gray-700">結束日期</th>
                    <th class="p-3 text-sm font-medium text-gray-700">適用商品</th>
                    <th class="p-3 text-sm font-medium text-gray-700">操作</th>
                </tr>
            </thead>
            <tbody id="discountTable">
                @forelse ($discounts as $discount)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm text-gray-600">{{ $discount->discount_id }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $discount->discount_name }}</td>
                        <td class="p-3 text-sm text-gray-600">
                            {{ $discount->discount_type == 'percentage' ? '百分比' : '固定金額' }}</td>
                        <td class="p-3 text-sm text-gray-600">
                            {{ $discount->discount_value }}{{ $discount->discount_type == 'percentage' ? '%' : '元' }}
                        </td>
                        <td class="p-3 text-sm text-gray-600">{{ $discount->start_date }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $discount->end_date }}</td>
                        <td class="p-3 text-sm text-gray-600">
                            @if ($discount->products->isNotEmpty())
                                <button type="button" onclick="toggleProducts({{ $discount->discount_id }})"
                                    class="text-blue-600 hover:underline">查看商品 ({{ $discount->products->count() }})</button>
                                <div id="products-{{ $discount->discount_id }}" class="hidden mt-2">
                                    <ul class="pl-5 list-disc">
                                        @foreach ($discount->products as $product)
                                            <li>{{ $product->product_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                無
                            @endif
                        </td>
                        <td class="p-3 text-sm">
                            <button onclick="openModal('edit', {{ $discount->discount_id }})"
                                class="text-blue-600 hover:underline">編輯</button>
                            <form action="{{ route('admin.discounts.destroy', $discount->discount_id) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('確定要刪除此折價？');"
                                    class="ml-2 text-red-600 hover:underline">刪除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-sm text-center text-gray-600">沒有找到折價記錄</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4" id="paginationLinks">
        {{ $discounts->appends(request()->query())->links() }}
    </div>

    <!-- Modal -->
    <div id="discountModal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-600 bg-opacity-50">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg">
            <h3 id="modalTitle" class="mb-4 text-lg font-semibold text-gray-800"></h3>
            <form id="discountForm" method="POST" action="/dashboard/discounts">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="discount_name" class="block text-sm font-medium text-gray-700">折價名稱</label>
                        <input type="text" name="discount_name" id="discount_name"
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p id="discount_name_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700">折價類型</label>
                        <select name="discount_type" id="discount_type"
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="percentage">百分比</option>
                            <option value="fixed_amount">固定金額</option>
                        </select>
                        <p id="discount_type_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-700">折價數值</label>
                        <input type="number" name="discount_value" id="discount_value"
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p id="discount_value_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">開始日期</label>
                        <input type="date" name="start_date" id="start_date"
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p id="start_date_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">結束日期</label>
                        <input type="date" name="end_date" id="end_date"
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p id="end_date_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                    <div>
                        <label for="discount_description" class="block text-sm font-medium text-gray-700">折價說明</label>
                        <textarea name="discount_description" id="discount_description" rows="4"
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        <p id="discount_description_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                    <div>
                        <label for="products" class="block text-sm font-medium text-gray-700">適用商品</label>
                        <select name="products[]" id="products" multiple
                            class="w-full px-4 py-2 mt-1 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                        <p id="products_error" class="hidden mt-1 text-sm text-red-600"></p>
                    </div>
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="submit"
                        class="px-4 py-2 text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">儲存</button>
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-white transition duration-200 bg-gray-500 rounded-lg hover:bg-gray-600">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) console.error('CSRF token not found.');

    function toggleProducts(discountId) {
        const productList = document.getElementById(`products-${discountId}`);
        productList.classList.toggle('hidden');
    }

    function openModal(mode, id = null) {
        const modal = document.getElementById('discountModal');
        const form = document.getElementById('discountForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');

        form.reset();
        document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));

        if (mode === 'create') {
            modalTitle.textContent = '新增折價';
            form.action = '/dashboard/discounts';
            formMethod.value = 'POST';
        } else if (mode === 'edit' && id) {
            modalTitle.textContent = '編輯折價';
            form.action = `/dashboard/discounts/${id}`;
            formMethod.value = 'PUT';

            let editUrl = '{{ route('admin.discounts.edit', ['id' => ':id']) }}'.replace(':id', id);
            editUrl = editUrl.replace('http://', 'https://');

            fetch(editUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status} ${response.statusText}`);
                return response.json();
            })
            .then(data => {
                document.getElementById('discount_name').value = data.discount_name || '';
                document.getElementById('discount_type').value = data.discount_type || 'percentage';
                document.getElementById('discount_value').value = data.discount_value || '';
                document.getElementById('start_date').value = data.start_date || '';
                document.getElementById('end_date').value = data.end_date || '';
                document.getElementById('discount_description').value = data.discount_description || '';
                const productsSelect = document.getElementById('products');
                Array.from(productsSelect.options).forEach(option => {
                    option.selected = data.products?.includes(parseInt(option.value)) || false;
                });
            })
            .catch(error => {
                console.error('Error fetching discount:', error);
                alert('無法載入折價資料，請檢查網絡或稍後再試');
            });
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('discountModal').classList.add('hidden');
    }

    document.getElementById('discountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const method = form.querySelector('#formMethod').value;
        let url = form.action;
        url = url.replace('http://', 'https://');

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || `HTTP error! Status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal();
                window.location.reload();
            } else {
                for (const [field, message] of Object.entries(data.errors || {})) {
                    const errorElement = document.getElementById(`${field}_error`);
                    if (errorElement) {
                        errorElement.textContent = message[0];
                        errorElement.classList.remove('hidden');
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            alert('操作失敗：' + error.message);
        });
    });
</script>
