<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>讓兄弟組</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #1f2937;
            --accent: #3b82f6;
        }

        .category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .add-on-button {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .add-on-button:hover {
            transform: scale(1.05);
        }

        .product-options {
            background: white;
        }

        .product-options li:hover {
            background: #f9fafb;
        }

        .quantity {
            min-width: 2rem;
        }

        .product-select-container button {
            transition: background-color 0.2s;
        }

        .product-list li {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            transition: background-color 0.2s;
        }

        .product-list li:last-child {
            border-bottom: none;
        }

        .product-list li.highlighted {
            background: #f0f5ff;
        }

        .thumbnail-list img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.2s, border 0.2s;
        }

        .thumbnail-list img:hover {
            transform: scale(1.1);
            border: 2px solid var(--accent);
        }

        .thumbnail-list img.active {
            border: 2px solid var(--accent);
        }

        .toggle-button svg {
            transition: transform 0.2s;
        }

        .next-step-button {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .next-step-button:hover {
            transform: scale(1.05);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased text-gray-800 bg-gray-50 font-figtree">
    <div class="flex flex-col min-h-screen">
        <!-- Header Component -->
        <x-header title="讓兄弟組 - 打造你的夢想電腦" />

        <!-- Main Content -->
        <main class="flex-1 px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Ad Carousel -->
            <button type="button"
                class="relative w-full mb-8 overflow-hidden text-left transition-shadow shadow-lg rounded-xl focus:outline-none hover:shadow-xl"
                onclick="onAdClick()">
                <img id="ad-banner" alt="廣告" class="object-cover w-full h-[50vh] rounded-xl">
                <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent">
                    <p id="ad-title" class="p-6 text-xl font-semibold text-white"></p>
                </div>
            </button>
        <!-- Progress Steps -->
        <x-progress-steps :progress_list="['選擇商品', '確認配置', '確認訂單']" :current_step="0" />
            <!-- Categories -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-1">
                @foreach ($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>

            <!-- Next Step Button -->
            <!-- Next Step Form -->
            <div class="mt-8 text-right">
                <button class="w-full p-4 font-semibold text-white bg-gray-700 rounded-lg" id="next-step-button">
                    下一步
                </button>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-white bg-gray-800 bg-secondary">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between sm:flex-row">
                    <p>© 2025 讓兄弟組. All rights reserved.</p>
                    <div class="flex mt-4 space-x-4 sm:mt-0">
                        <a href="#" class="hover:text-gray-300">關於我們</a>
                        <a href="#" class="hover:text-gray-300">聯繫我們</a>
                        <a href="#" class="hover:text-gray-300">隱私政策</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Login Prompt Modal -->
        <div id="login-prompt"
            class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm">
            <div class="w-full max-w-md p-6 bg-white shadow-lg rounded-xl">
                <h3 class="mb-4 text-xl font-semibold text-gray-900">請先登入</h3>
                <p class="mb-6 text-gray-600">您需要登入才能選擇商品或加購選項。請登入或註冊以繼續。</p>
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('user.login') }}"
                        class="px-4 py-2 text-white rounded-md bg-accent hover:bg-blue-700">登入</a>
                    <a href="{{ route('user.register') }}"
                        class="px-4 py-2 text-white bg-gray-600 rounded-md hover:bg-gray-700">註冊</a>
                </div>
            </div>
        </div>


    </div>

    <!-- Ad Carousel and Product Selection Script -->
    <script>
        const isAuthenticated = @json(auth()->check());
        let ads = @json($ads);
        let index = 0;
        let ad = ads[index];
        const selectedProducts =
            new Map(); // 儲存選擇的商品：{ categoryId-productId: { product_id, name, description, price, image, quantity, category_id } }

        // 廣告輪播
        function updateAdImage() {
            if (ads.length === 0) return;
            ad = ads[index];
            const banner = document.getElementById("ad-banner");
            const title = document.getElementById("ad-title");
            banner.src = `/images/ads/${ad.ad_banner}.png?t=${Date.now()}`;
            banner.alt = ad.ad_title;
            title.textContent = ad.ad_title;
            index = (index + 1) % ads.length;
        }

        function onAdClick() {
            window.location.href = `/ad/${ad.ad_id}`;
        }

        // 相容性檢查
        async function checkCompatibility(productId, accessoryId, categoryName) {
            try {
                const response = await fetch('/api/products/compatibility', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        accessory_id: accessoryId
                    }),
                });
                const data = await response.json();
                return response.ok ? {
                    compatible: true,
                    message: `與 ${categoryName} 相容`
                } : {
                    compatible: false,
                    message: data.error || `與 ${categoryName} 不相容`
                };
            } catch (error) {
                console.error('相容性檢查失敗：', error);
                return {
                    compatible: false,
                    message: `無法檢查與 ${categoryName} 的相容性`
                };
            }
        }

        // 載入商品列表
        async function loadProducts(button) {
            if (!isAuthenticated) {
                document.getElementById('login-prompt').classList.remove('hidden');
                return;
            }

            const container = button.closest('.product-select-container');
            const categoryId = container.dataset.categoryId;
            const optionsList = container.querySelector('.product-options');
            const apiUrl = `https://api.qiushawa.studio/products/category?id=${categoryId}`;

            if (button.dataset.loaded === 'true') {
                optionsList.classList.toggle('hidden');
                return;
            }

            try {
                const response = await fetch(apiUrl);
                if (!response.ok) throw new Error('無法載入產品');
                const data = await response.json();
                const products = data.products;

                optionsList.innerHTML = '';
                products.forEach(product => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center justify-between p-2 border-b border-gray-200';
                    li.innerHTML = `
                        <div class="flex-1">
                            <span>${product.product_name}</span>
                            <p class="text-sm text-gray-500">${product.product_description}</p>
                            <span class="text-sm text-gray-600">NT$${product.product_price}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="updateQuantity('${categoryId}', '${product.product_id}', -1, this)" class="px-2 py-1 bg-gray-200 rounded">-</button>
                            <span class="w-8 text-center quantity" data-product-id="${product.product_id}">0</span>
                            <button onclick="updateQuantity('${categoryId}', '${product.product_id}', 1, this)" class="px-2 py-1 bg-gray-200 rounded">+</button>
                        </div>
                    `;
                    li.dataset.productId = product.product_id;
                    li.dataset.productName = product.product_name;
                    li.dataset.productDescription = product.product_description;
                    li.dataset.productPrice = product.product_price;
                    li.dataset.productImage = product.product_image || 'none';
                    optionsList.appendChild(li);
                });

                optionsList.classList.remove('hidden');
                button.dataset.loaded = 'true';
            } catch (error) {
                console.error('載入失敗：', error);
                alert('無法載入產品，請稍後再試');
            }
        }

        // 更新商品數量
        async function updateQuantity(categoryId, productId, change, button) {
            if (!isAuthenticated) {
                document.getElementById('login-prompt').classList.remove('hidden');
                return;
            }

            const container = button.closest('.product-select-container');
            const quantityElement = container.querySelector(`.quantity[data-product-id="${productId}"]`);
            let currentQuantity = parseInt(quantityElement.textContent) || 0;
            currentQuantity = Math.max(0, currentQuantity + change);
            quantityElement.textContent = currentQuantity;

            const li = button.closest('li');
            if (currentQuantity > 0) {
                selectedProducts.set(`${categoryId}-${productId}`, {
                    product_id: productId,
                    name: li.dataset.productName,
                    description: li.dataset.productDescription,
                    price: parseFloat(li.dataset.productPrice),
                    image: li.dataset.productImage,
                    quantity: currentQuantity,
                    category_id: categoryId,
                });
            } else {
                selectedProducts.delete(`${categoryId}-${productId}`);
            }

            await updateProductDisplay(categoryId);
        }

        // 更新商品顯示
        async function updateProductDisplay(categoryId) {
            const card = document.querySelector(`.product-select-container[data-category-id="${categoryId}"]`).closest(
                'section');
            const productList = card.querySelector('.product-list');
            const totalPriceElement = card.querySelector('.total-price');
            const thumbnailList = card.querySelector('.thumbnail-list');
            const categoryIcon = card.querySelector('.product-select-container').dataset.categoryIcon;

            productList.innerHTML = '';
            thumbnailList.innerHTML = '';
            let totalPrice = 0;
            let selectedCount = 0;

            for (const [key, product] of selectedProducts) {
                if (product.category_id !== categoryId) continue;

                selectedCount++;
                totalPrice += product.price * product.quantity;

                // 添加商品資訊到列表
                const li = document.createElement('li');
                li.className = 'mb-2 product-item';
                li.dataset.productId = product.product_id;
                li.innerHTML = `
                    <div class="flex justify-between">
                        <span>${product.name} (x${product.quantity})</span>
                        <span class="text-gray-600">NT$${ (product.price * product.quantity).toFixed(2) }</span>
                    </div>
                    <p class="text-sm text-gray-500">${product.description}</p>
                `;
                productList.appendChild(li);

                // 添加縮圖
                const img = document.createElement('img');
                img.src = product.image && product.image !== 'none' ?
                    `/images/products/${product.image}.png?t=${Date.now()}` :
                    `/images/icons/${categoryIcon}.png?t=${Date.now()}`;
                img.alt = product.name || '類別圖示';
                img.dataset.productId = product.product_id;
                img.onclick = () => highlightProduct(categoryId, product.product_id);
                thumbnailList.appendChild(img);


            }

            if (selectedCount === 0) {
                productList.innerHTML = '<li class="text-gray-600">尚未選擇商品</li>';
                totalPriceElement.textContent = '總價格：尚未選擇';
                const img = document.createElement('img');
                img.src = `/images/icons/${categoryIcon}.png?t=${Date.now()}`;
                img.alt = '類別圖示';
                thumbnailList.appendChild(img);
            } else {
                totalPriceElement.textContent = `總價格：NT$${totalPrice.toFixed(2)}`;
            }

            // 預設高亮第一個商品（如果有）
            if (selectedCount > 0) {
                const firstProductId = Array.from(selectedProducts.values()).find(p => p.category_id === categoryId)
                    ?.product_id;
                if (firstProductId) highlightProduct(categoryId, firstProductId);
            }
        }

        // 高亮選定商品
        function highlightProduct(categoryId, productId) {
            const card = document.querySelector(`.product-select-container[data-category-id="${categoryId}"]`).closest(
                'section');
            const productItems = card.querySelectorAll('.product-item');
            const thumbnails = card.querySelectorAll('.thumbnail-list img');

            productItems.forEach(item => {
                item.classList.toggle('highlighted', item.dataset.productId === productId);
            });

            thumbnails.forEach(img => {
                img.classList.toggle('active', img.dataset.productId === productId);
            });
        }

        // 下一步：提交所有選中商品
        // 下一步：提交所有選中商品
        function goToNextStep(event) {
            event?.preventDefault(); // Prevent default form submission if called from form

            const selectedItems = Array.from(selectedProducts.values()).map(product => ({
                product_id: product.product_id,
                quantity: product.quantity
            }));

            if (selectedItems.length === 0) {
                alert('請至少選擇一個商品');
                return;
            }

            // Set the items in the hidden input
            document.getElementById('items-input').value = JSON.stringify(selectedItems);

            // Submit the form
            document.getElementById('next-step-form').submit();
        }

        // 初始化廣告輪播
        updateAdImage();
        setInterval(updateAdImage, 3000);
    </script>
    <script>
    // 收集所有已選擇商品的資料
function collectSelectedProducts() {
    const details = [];
    let hasSelection = false;

    // Iterate over selectedProducts Map
    for (const [key, product] of selectedProducts) {
        if (product.quantity > 0) {
            details.push({
                product_id: product.product_id,
                quantity: product.quantity,
                unit_price: product.price,
                discount_amount: 0 // Adjust if you have discounts
            });
            hasSelection = true;
        }
    }

    if (!hasSelection) {
        alert('請至少選擇一個商品！');
        return null;
    }

    return {
        config_name: 'Custom Configuration ' + new Date().toLocaleString(),
        details: details
    };
}

    // 提交配置
    async function submitConfiguration() {
        const data = collectSelectedProducts();
        if (!data) return;
        console.log('提交的配置數據：', data);
        try {
            const response = await fetch('/custom-configuration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                // 跳轉到確認頁面
                window.location.href = `/configuration/${result.configuration_id}`;
            } else {
                alert('提交失敗：' + result.message);
            }
        } catch (error) {
            console.error('提交錯誤：', error);
            alert('發生錯誤，請稍後重試。');
        }
    }

    // 綁定“下一步”按鈕
    document.getElementById('next-step-button').addEventListener('click', submitConfiguration);
</script>
</body>

</html>
