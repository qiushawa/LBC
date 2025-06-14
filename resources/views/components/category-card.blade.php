<!-- resources/views/components/category-card.blade.php -->
<section class="relative p-6 overflow-hidden transition-shadow bg-white shadow-sm category-card rounded-xl hover:shadow-md animate-fade-in" aria-label="商品類別">
    <div class="flex flex-col gap-6 sm:flex-row">
        <div class="flex-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">{{ $category->category_name }}</h2>
                <button onclick="openAddOnModal('{{ $category->category_id }}')" class="add-on-button px-3 py-1.5 bg-accent text-white rounded-md text-sm hover:bg-blue-700">加購選項</button>
            </div>
            <div class="md:w-full">
                <div class="product-select-container" data-category-id="{{ $category->category_id }}" data-category-icon="{{ $category->category_icon }}">
                    <button onclick="loadProducts(this)" class="w-full p-3 text-left bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-accent" data-loaded="false">
                        載入 {{ $category->category_name }} 商品
                    </button>
                    <ul class="hidden mt-2 overflow-y-auto border border-gray-300 rounded-md product-options max-h-48"></ul>
                </div>
            </div>
            <div class="p-4 mt-4 text-sm text-gray-600 border border-dashed rounded-lg product-info-container" x-data="{ isOpen: true }">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-800">已選擇商品</h3>
                    <button @click="isOpen = !isOpen" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <div x-show="isOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-[500px]" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-[500px]" x-transition:leave-end="opacity-0 max-h-0" class="overflow-hidden">
                    <ul class="mt-2 text-gray-600 product-list"></ul>
                    <p class="mt-2 font-semibold text-gray-800 total-price">總價格：尚未選擇</p>
                </div>
            </div>
        </div>
        <div class="flex-shrink-0 sm:w-[30%] aspect-[1/1] h-[30vh] product-image-container">
            <div class="flex flex-wrap h-full gap-2 overflow-y-auto rounded-lg thumbnail-list">
                <!-- 縮圖將由 JavaScript 動態生成 -->
            </div>
        </div>
    </div>
</section>
