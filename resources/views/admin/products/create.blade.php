@extends('layouts.app', ['page_name' => '新增商品', 'show_footer' => false, 'show_header' => false])

@section('content')
    <div class="min-h-screen p-4 bg-gray-50 sm:p-6 lg:p-8">
        <div class="max-w-lg p-6 mx-auto bg-white rounded-lg shadow-md">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">新增商品</h3>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">商品類別</label>
                    <select name="category_id" id="category_id"
                            class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach ($categorys as $category)
                            <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700">供應商</label>
                    <select name="supplier_id" id="supplier_id"
                            class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="product_name" class="block text-sm font-medium text-gray-700">商品名稱</label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('product_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="product_description" class="block text-sm font-medium text-gray-700">商品描述</label>
                    <textarea name="product_description" id="product_description"
                              class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('product_description') }}</textarea>
                    @error('product_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                                <div class="mb-4">
                    <label for="product_price" class="block text-sm font-medium text-gray-700">商品價格</label>
                    <input type="number" name="product_price" id="product_price" value="{{ old('product_price') }}"
                           step="0.01" min="0"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('product_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="product_image" class="block text-sm font-medium text-gray-700">商品圖片</label>
                    <input type="file" name="product_image" id="product_image"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           class="block w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('product_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="image-preview" class="hidden mt-2">
                        <img id="preview-img" src="#" alt="商品圖片預覽" class="h-auto max-w-full rounded-lg shadow-sm" style="max-height: 200px;">
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.products') }}"
                       class="px-4 py-2 text-gray-600 hover:text-gray-800">取消</a>
                    <button type="submit"
                            class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">新增</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('product_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (file) {
                // Check if the file is an image
                if (!file.type.match('image/jpeg|image/png|image/jpg|image/gif')) {
                    alert('請上傳有效的圖片格式 (JPEG, PNG, JPG, GIF)');
                    event.target.value = ''; // Clear the input
                    previewContainer.classList.add('hidden');
                    return;
                }

                // Display the preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                // Hide preview if no file is selected
                previewContainer.classList.add('hidden');
            }
        });
    </script>
@endsection
