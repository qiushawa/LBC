<section
    class="col-span-1 sm:col-span-12 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow min-h-[150px] animate-fade-in relative overflow-hidden"
    aria-label="員工資訊">
    <div class="flex flex-col justify-between h-full sm:flex-row">
        <!-- 員工資訊內容 -->
        <div class="flex-1 p-6 sm:w-[45%]">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">{{ $category->category_name }}</h2>
            <!-- Flex container for select menu and product info -->
            <div class="flex flex-col gap-6 md:flex-row">
                <!-- Left: Select Menu -->
                <div class="md:w-1/3">
                    <select name="product[{{ $category->category_id }}]" id="{{ $category->category_id }}"
                        class="w-full p-3 border border-gray-300 rounded-md product-select focus:outline-none focus:ring-2 focus:ring-blue-500"
                        data-loaded="false" onchange="loadProductInfo(this)">
                        <option value="">請選擇 {{ $category->category_name }} 商品</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <!-- Right: Product Info and Image -->
                <div class="flex flex-col gap-4 md:w-2/3 md:flex-row">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-800 product-name">請選擇商品</h3>
                        <p class="mt-2 text-gray-600 product-description">商品描述將顯示在這裡</p>
                        <p class="mt-2 font-semibold text-gray-800 product-price">價格：尚未選擇</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- 圖片 -->
        <div class="flex-shrink-0 sm:w-[55%] ml-auto relative aspect-[16/9] group">
            <img src="{{ asset('images/icons/' . $category->category_icon . '.png') }}" alt="員工資訊圖片"
                class="object-cover w-full h-full rounded-tr-lg rounded-br-lg product-image">
            <div class="absolute inset-0 rounded-tr-lg rounded-br-lg bg-gradient-to-r from-white to-transparent"></div>
        </div>
    </div>
</section>
