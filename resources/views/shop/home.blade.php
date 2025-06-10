<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>讓兄弟組</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-gray-800 bg-gray-100 font-figtree">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-900">讓兄弟組 - 組裝你的夢想電腦</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Ad Carousel -->

            <button type="button"
                class="relative w-full mb-8 overflow-hidden text-left rounded-lg shadow-lg focus:outline-none"
                onclick="onAdClick()">
                <img id="ad-banner" alt="廣告" class="object-cover w-full h-[50vh]">
                <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/50 to-transparent">
                    <p id="ad-title" class="p-4 text-lg font-semibold text-white"></p>
                </div>
            </button>
            </a>
            <div class="grid grid-cols-1 gap-6">
                @foreach ($categories as $category)
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <!-- Category Card Header -->
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
                                <div class="md:w-1/3">
                                    <img src="{{asset("images/icons/".$category->category_icon.'.png')}}" alt="Product Image"
                                        class="object-cover w-full h-48 rounded-md product-image">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-white bg-gray-800">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <p>&copy; 2025 讓兄弟組. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Ad Carousel Script -->
    <script>
        let ads = @json($ads);
        let index = 0;
        let ad = ads[index];

        function updateAdImage() {
            if (ads.length === 0) return;
            ad = ads[index];
            let banner = document.getElementById("ad-banner");
            let title = document.getElementById("ad-title");
            banner.src = `/images/ads/${ad.ad_banner}.png?t=${{ time() }}`;
            banner.alt = ad.ad_title;
            title.textContent = ad.ad_title;

            index = (index + 1) % ads.length;
        }

        function onAdClick() {
            const adId = ad.ad_id;
            console.log(`廣告 ID: ${adId}`);
            window.location.href = `https://shop.qiushawa.studio/ad/${adId}`;
        }

        function loadProductInfo(select) {
            const selectedOption = select.options[select.selectedIndex];
            const productName = selectedOption.getAttribute('data-name') || '請選擇商品';
            const productDescription = selectedOption.getAttribute('data-description') || '商品描述將顯示在這裡';
            const productPrice = selectedOption.getAttribute('data-price') || '價格：尚未選擇';
            const productImage = selectedOption.getAttribute('data-image') || 'https://via.placeholder.com/150';

            const card = select.closest('.p-6');
            card.querySelector('.product-name').textContent = productName;
            card.querySelector('.product-description').textContent = productDescription;
            card.querySelector('.product-price').textContent = `價格：${productPrice}`;
            card.querySelector('.product-image').src = productImage;
        }
        updateAdImage();
        setInterval(updateAdImage, 3000);
    </script>

    <!-- Product Selection Script -->
    <script>
        document.querySelectorAll('.product-select').forEach(select => {
            select.addEventListener('click', async function() { // Changed from 'click' to 'change'
                const categoryId = this.id;
                const apiUrl = `https://api.qiushawa.studio/products/category?id=${categoryId}`;
                console.log(`載入類別 ${categoryId} 的產品... API URL: ${apiUrl}`);
                if (this.dataset.loaded === 'true') return;

                try {
                    const response = await fetch(apiUrl);
                    if (!response.ok) throw new Error('無法載入產品');

                    const data = await response.json();
                    const products = data.products;

                    this.length = 1;

                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.product_id;
                        option.textContent =
                            `${product.product_name} ($${product.product_price})`;
                        this.appendChild(option);
                    });

                    this.dataset.loaded = 'true';
                } catch (error) {
                    console.error('載入失敗：', error);
                    alert('無法載入產品，請稍後再試');
                }
            });
        });
    </script>
</body>

</html>
