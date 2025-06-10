<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5.5.1/github-markdown.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<style>
    .markdown-body {
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    .EasyMDEContainer .editor-preview {
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
        line-height: 1.5;
        color: #333;
    }

    .EasyMDEContainer .editor-preview.markdown-body {
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .EasyMDEContainer .editor-preview * {
        font-family: inherit;
        line-height: inherit;
        color: inherit;
    }

    .EasyMDEContainer .editor-preview h1,
    .EasyMDEContainer .editor-preview h2,
    .EasyMDEContainer .editor-preview h3,
    .EasyMDEContainer .editor-preview h4,
    .EasyMDEContainer .editor-preview h5,
    .EasyMDEContainer .editor-preview h6 {
        font-weight: 600;
        line-height: 1.25;
        margin-bottom: 16px;
    }

    .EasyMDEContainer .editor-preview p {
        margin-bottom: 16px;
    }

    .EasyMDEContainer .editor-preview a {
        color: #0366d6;
        text-decoration: none;
    }

    .EasyMDEContainer .editor-preview a:hover {
        text-decoration: underline;
    }

    .banner-container {
        position: relative;
        overflow: hidden;
        aspect-ratio: 23/9;
        max-height: 80px;
    }

    .banner-image {
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-radius: 8px;
    }

    .banner-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(255, 255, 255, 0.2), transparent);
        border-radius: 8px;
    }

    .banner-upload-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(31, 41, 55, 0.8);
        color: white;
        padding: 6px;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }

    .banner-container:hover .banner-upload-btn {
        opacity: 1;
    }

    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }

    .modal.hidden {
        display: none;
    }

    .modal-content {
        background: white;
        padding: 24px;
        border-radius: 8px;
        max-width: 500px;
        width: 100%;
    }

    .content-grid {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 20px;
        align-items: start;
        margin-top: 20px;
    }


    #adTable-table {
        width: 100% !important;
        /* Override inline w-[45vw] */
    }

    .editor-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: none;
        min-height: 0;
    }

    .editor-container.active {
        display: block;
    }

    .editor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .editor-content {
        margin-bottom: 20px;
    }

    .banner-upload-preview {
        margin-top: 10px;
    }

    .banner-upload-preview img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .editor-container {
            max-width: 100%;
        }

        table {
            width: 100% !important;
        }
    }
</style>

<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3 w-[50vw]">
    <div class="p-6 transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800">總廣告數</h3>
        <span class="text-3xl font-bold text-blue-600">{{ $totalAds }}</span>/則
    </div>
</div>



<div class="w-full overflow-x-auto">
    <div class="w-[50vw] overflow-x-auto table-container">
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-800">廣告列表</h3>
            <form method="GET" action="{{ route('admin.ads') }}"
                class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto">
                <div class="flex gap-2">
                    <button type="button" onclick="openEditor(null);"
                        class="px-4 py-2 text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700">新增廣告</button>
                </div>
            </form>
        </div>
        <table class="w-[50vw] text-left" id="adTable-table">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-sm font-medium text-gray-700">編號</th>
                    <th class="p-3 text-sm font-medium text-gray-700">橫幅</th>
                    <th class="p-3 text-sm font-medium text-gray-700">廣告標題</th>
                    <th class="p-3 text-sm font-medium text-gray-700">操作</th>
                </tr>
            </thead>
            <tbody id="adTable">
                @forelse ($ads as $index => $ad)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-lg text-gring-offset-gray-600">{{ $index + 1 }}</td>
                        <td class="p-3 text-lg text-gray-600">
                            @if ($ad->banner_url)
                                <div class="banner-container group">
                                    <img src="{{ str_replace('http://', 'https://', asset($ad->banner_url)) }}?t={{ time() }}"
                                        alt="Ad Banner" class="banner-image" data-ad-id="{{ $ad->ad_id }}">
                                    <div class="banner-gradient"></div>
                                    <button class="banner-upload-btn" title="更換橫幅"
                                        onclick="openUploadModal('{{ $ad->ad_id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-500">無橫幅</span>
                                <button class="px-2 py-1 mt-2 text-white bg-blue-600 rounded-md"
                                    onclick="openUploadModal('{{ $ad->ad_id }}')">上傳橫幅</button>
                            @endif
                        </td>
                        <td class="p-3 text-lg text-gray-600"><b>{{ $ad->ad_title }}</b></td>
                        <td class="p-3 text-sm">
                            <button class="text-blue-600 hover:underline"
                                onclick="openEditor('{{ $ad->ad_id }}'); return false;">編輯</button>
                            <form action="{{ route('admin.ads.destroy', $ad->ad_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('確定要刪除此員工？');"
                                    class="ml-2 text-red-600 hover:underline">刪除</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-sm text-center text-gray-500">沒有找到符合條件的廣告</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Editor Div -->
    <div id="editorContainer" class="editor-container">
        <div class="editor-header">
            <h2 class="text-lg font-semibold text-gray-800" id="editorTitle">編輯廣告</h2>
            <button onclick="closeEditor()" class="text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="editor-content">
            <input type="hidden" id="editorAdId">
            <label for="adTitle" class="block text-sm font-medium text-gray-700">廣告標題</label>
            <input type="text" id="adTitle" class="block w-full mt-1 mb-4 border-gray-300 rounded-md">
            <label for="bannerUpload" class="block text-sm font-medium text-gray-700">上傳廣告橫幅</label>
            <input type="file" id="bannerUpload" name="banner" accept="image/png, image/jpeg"
                class="block w-full mt-1 mb-4 border-gray-300 rounded-md">
            <p class="mt-2 text-sm text-gray-500">僅支援 PNG 和 JPEG 格式</p>
            <div id="bannerPreview" class="hidden banner-upload-preview">
                <p class="text-sm font-medium text-gray-700">橫幅預覽：</p>
                <img id="bannerPreviewImg" src="#" alt="橫幅預覽" class="mt-2">
            </div>
            <label for="editorContent" class="block text-sm font-medium text-gray-700">廣告內容 (Markdown)</label>
            <textarea id="editorContent"></textarea>
        </div>
        <div class="flex justify-end">
            <button onclick="closeEditor()" class="px-4 py-2 mr-2 text-gray-800 bg-gray-200 rounded-md">取消</button>
            <button onclick="saveAd()" class="px-4 py-2 text-white bg-blue-600 rounded-md">儲存</button>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div id="uploadModal" class="hidden modal">
    <div class="modal-content">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">上傳廣告橫幅</h2>
        <form id="uploadForm" action="{{ route('admin.ads.upload-banner') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="adId" name="ad_id">
            <div class="mb-4">
                <label for="imageUpload" class="block text-sm font-medium text-gray-700">選擇圖片</label>
                <input type="file" id="imageUpload" name="banner" accept="image/png, image/jpeg"
                    class="block w-full mt-1 border-gray-300 rounded-md">
                <p class="mt-2 text-sm text-gray-500">僅支援 PNG 和 JPEG 格式</p>
            </div>
            <div id="imagePreview" class="hidden mt-4">
                <p class="text-sm font-medium text-gray-700">圖片預覽：</p>
                <img id="previewImg" src="#" alt="圖片預覽" class="h-auto max-w-full mt-2 rounded-md">
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" class="px-4 py-2 mr-2 text-gray-800 bg-gray-200 rounded-md"
                    onclick="closeUploadModal()">取消</button>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md">上傳</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>
<script>
    // Initialize EasyMDE
    let easymde = null;
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('uploadModal');
        const editorContainer = document.getElementById('editorContainer');
        modal.classList.add('hidden');
        editorContainer.classList.remove('active');

        const editorContent = document.getElementById('editorContent');
        if (editorContent) {
            easymde = new EasyMDE({
                element: editorContent,
                spellChecker: false,
                status: false,
                toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list",
                    "|", "link", "image", "|", "preview", "side-by-side", "fullscreen"
                ],
                renderingConfig: {
                    previewClass: ['markdown-body']
                }
            });

            easymde.codemirror.on('refresh', () => {
                const previewElement = document.querySelector('.editor-preview');
                if (previewElement && !previewElement.classList.contains('markdown-body')) {
                    previewElement.classList.add('markdown-body');
                }
            });
        } else {
            console.error('Element with ID "editorContent" not found');
        }

        // Handle image upload for modal
        const uploadForm = document.getElementById('uploadForm');
        uploadForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(uploadForm);
            const actionUrl = uploadForm.action;

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                });

                if (response.ok) {
                    closeUploadModal();
                    window.location = window.location.pathname + '?t=' + new Date().getTime();
                } else {
                    const errorData = await response.json();
                    alert('上傳失敗：' + (errorData.message || '請稍後再試'));
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('上傳失敗，請檢查網絡連線或稍後再試');
            }
        });

        // Image upload preview for modal
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        if (imageUpload && imagePreview && previewImg) {
            imageUpload.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                    previewImg.src = '#';
                }
            });
        }

        // Banner upload preview in editor
        const bannerUpload = document.getElementById('bannerUpload');
        const bannerPreview = document.getElementById('bannerPreview');
        const bannerPreviewImg = document.getElementById('bannerPreviewImg');

        if (bannerUpload && bannerPreview && bannerPreviewImg) {
            bannerUpload.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        bannerPreviewImg.src = e.target.result;
                        bannerPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    bannerPreview.classList.add('hidden');
                    bannerPreviewImg.src = '#';
                }
            });
        }
    });

    function openUploadModal(adId) {
        const modal = document.getElementById('uploadModal');
        const adIdInput = document.getElementById('adId');
        adIdInput.value = adId;
        modal.classList.remove('hidden');
    }

    function closeUploadModal() {
        const modal = document.getElementById('uploadModal');
        const uploadForm = document.getElementById('uploadForm');
        const imagePreview = document.getElementById('imagePreview');
        modal.classList.add('hidden');
        uploadForm.reset();
        imagePreview.classList.add('hidden');
    }

    async function openEditor(adId) {
        const editorContainer = document.getElementById('editorContainer');
        const editorTitle = document.getElementById('editorTitle');
        const adTitleInput = document.getElementById('adTitle');
        const editorAdId = document.getElementById('editorAdId');
        const adTabletable = document.getElementById('adTable-table');
        const bannerUpload = document.getElementById('bannerUpload');
        const bannerPreview = document.getElementById('bannerPreview');

        adTabletable.style.display = 'none';
        editorContainer.classList.add('active');

        if (adId) {
            // Editing an existing ad
            editorTitle.textContent = '編輯廣告';
            try {
                const response = await fetch(`/dashboard/ads/${adId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    editorAdId.value = adId;
                    adTitleInput.value = data.ad_title || '';
                    if (easymde) {
                        easymde.value(data.content || '');
                    }
                    bannerUpload.value = ''; // Clear file input
                    bannerPreview.classList.add('hidden');
                } else {
                    alert('無法載入廣告數據，請稍後再試');
                    closeEditor();
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('無法載入廣告數據，請檢查網絡連線');
                closeEditor();
            }
        } else {
            // Creating a new ad
            editorTitle.textContent = '新增廣告';
            editorAdId.value = '';
            adTitleInput.value = '';
            if (easymde) {
                easymde.value('');
            }
            bannerUpload.value = '';
            bannerPreview.classList.add('hidden');
        }
    }

    function closeEditor() {
        const editorContainer = document.getElementById('editorContainer');
        const adTabletable = document.getElementById('adTable-table');
        const bannerUpload = document.getElementById('bannerUpload');
        const bannerPreview = document.getElementById('bannerPreview');

        editorContainer.classList.remove('active');
        adTabletable.style.display = 'table';
        document.getElementById('adTitle').value = '';
        document.getElementById('editorAdId').value = '';
        if (easymde) {
            easymde.value('');
        }
        bannerUpload.value = '';
        bannerPreview.classList.add('hidden');
    }

    async function saveAd() {
        const adId = document.getElementById('editorAdId').value;
        const adTitle = document.getElementById('adTitle').value;
        const content = easymde ? easymde.value() : '';
        const bannerUpload = document.getElementById('bannerUpload');
        const formData = new FormData();
        formData.append('ad_title', adTitle);
        formData.append('content', content);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        if (bannerUpload.files[0]) {
            formData.append('banner', bannerUpload.files[0]);
        }

        const url = adId ? `/dashboard/ads/${adId}` : '/dashboard/ads';
        const method = adId ? 'PUT' : 'POST';
        if (adId) {
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST', // Use POST with _method for Laravel
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });

            if (response.ok) {
                closeEditor();
                window.location = window.location.pathname + '?t=' + new Date().getTime();
            } else {
                const errorData = await response.json();
                alert('儲存失敗：' + (errorData.message || '請稍後再試'));
            }
        } catch (error) {
            console.error('Save error:', error);
            alert('儲存失敗，請檢查網絡連線或稍後再試');
        }
    }
</script>
