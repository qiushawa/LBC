<div class="p-6 bg-white rounded-lg shadow-md">
    <h3 class="mb-6 text-lg font-semibold text-gray-800">新增折價</h3>
    <form method="POST" action="{{ route('admin.discounts.store') }}">
        @csrf
        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
            <div>
                <label for="discount_name" class="block text-sm font-medium text-gray-700">折價名稱</label>
                <input type="text" name="discount_name" id="discount_name" value="{{ old('discount_name') }}"
                       class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('discount_name') border-red-500 @enderror">
                @error('discount_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="discount_type" class="block text-sm font-medium text-gray-700">折價類型</label>
                <select name="discount_type" id="discount_type" class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('discount_type') border-red-500 @enderror">
                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>百分比</option>
                    <option value="fixed_amount" {{ old('discount_type') == 'fixed_amount' ? 'selected' : '' }}>固定金額</option>
                </select>
                @error('discount_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="discount_value" class="block text-sm font-medium text-gray-700">折價數值</label>
                <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}"
                       class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('discount_value') border-red-500 @enderror">
                @error('discount_value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">開始日期</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                       class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">結束日期</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                       class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mb-4">
            <label for="discount_description" class="block text-sm font-medium text-gray-700">折價說明</label>
            <textarea name="discount_description" id="discount_description" rows="4"
                      class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('discount_description') border-red-500 @enderror">{{ old('discount_description') }}</textarea>
            @error('discount_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="products" class="block text-sm font-medium text-gray-700">適用商品</label>
            <select name="products[]" id="products" multiple
                    class="mt-1 w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('products') border-red-500 @enderror">
                @foreach ($products as $product_id => $product_name)
                    <option value="{{ $product_id }}" {{ in_array($product_id, old('products', [])) ? 'selected' : '' }}>{{ $product_name }}</option>
                @endforeach
            </select>
            @error('products')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">儲存</button>
            <a href="{{ route('admin.discounts') }}" class="px-4 py-2 text-white transition duration-200 bg-gray-500 rounded-lg hover:bg-gray-600">取消</a>
        </div>
    </form>
</div>
