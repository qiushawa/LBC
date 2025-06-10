<!-- resources/views/dashboard.blade.php -->
<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <!-- 標題區 -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">歡迎，<span class="text-blue-600">{{ is_string(Auth::guard('employee')->user()->employee_name) ? Auth::guard('employee')->user()->employee_name : '用戶' }}</span></h1>
            <p class="text-sm text-gray-500">以下是您的帳戶資訊與權限概覽</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-full" aria-describedby="permission-desc">
                權限等級：{{ is_numeric($permissionLevel) ? $permissionLevel : '未知' }}
            </span>
            <p id="permission-desc" class="sr-only">您的帳戶權限等級，決定可訪問的功能</p>
        </div>
    </div>

    <!-- 卡片區 -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
        <!-- 第一排：員工資訊 -->
<section class="col-span-1 sm:col-span-12 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow min-h-[150px] animate-fade-in relative overflow-hidden" aria-label="員工資訊">
    <div class="flex flex-col justify-between h-full sm:flex-row">
        <!-- 員工資訊內容 -->
        <div class="flex-1 p-6 sm:w-[45%]">
            <h3 class="text-lg font-semibold text-gray-800">員工資訊</h3>
            <p class="mt-2 text-sm text-gray-600">您的權限等級為：{{ is_numeric($permissionLevel) ? $permissionLevel : '未知' }}</p>
            <p class="mt-1 text-sm text-gray-600">職位：{{ is_string(Auth::guard('employee')->user()->permission->job_title) ? Auth::guard('employee')->user()->permission->job_title : '未知' }}</p>
            <p class="mt-1 text-sm text-gray-600">電子郵件：{{ is_string(Auth::guard('employee')->user()->employee_email) ? Auth::guard('employee')->user()->employee_email : '未知' }}</p>
            <div class="p-4 mt-4 text-sm text-gray-600 border border-dashed rounded-lg">
                <p>您可以訪問以下功能：</p>
                <ul class="list-disc list-inside">
                    @if ($permissionLevel >= 1)
                        <li>查看庫存狀態</li>
                    @endif
                    @if ($permissionLevel >= 2)
                        <li>管理庫存</li>
                        <li>管理廣告</li>
                    @endif
                    @if ($permissionLevel >= 3)
                        <li>管理員工</li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- 圖片 -->
        <div class="flex-shrink-0 sm:w-[55%] ml-auto relative aspect-[16/9] group">
            @php
                $email = Auth::guard('employee')->user()->employee_email ?? 'default';
                $imageUrl = route('employee.image', ['filename' => $email . '.png']);
                $defaultImageUrl = route('employee.image', ['filename' => 'default.png']);
                $imageExists = Storage::disk('employee_images')->exists($email . '.png') ? $imageUrl : $defaultImageUrl;
            @endphp
            <img src="{{ $imageExists }}" alt="員工資訊圖片" class="object-cover w-full h-full rounded-tr-lg rounded-br-lg">
            <div class="absolute inset-0 rounded-tr-lg rounded-br-lg bg-gradient-to-r from-white to-transparent"></div>
            <button class="absolute p-2 text-white transition-opacity bg-gray-800 rounded-full opacity-0 top-2 right-2 group-hover:opacity-100" title="更換圖片" onclick="openUploadModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- 圖片上傳模態框 -->
    <div id="uploadModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">上傳新圖片</h2>
            <form id="uploadForm" action="{{ route('employee.upload-image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="imageUpload" class="block text-sm font-medium text-gray-700">選擇圖片</label>
                    <input type="file" id="imageUpload" name="image" accept="image/png, image/jpeg" class="block w-full mt-1 border-gray-300 rounded-md">
                    <p class="mt-2 text-sm text-gray-500">僅支援 PNG 和 JPEG 格式</p>
                </div>
                <div id="imagePreview" class="hidden mt-4">
                    <p class="text-sm font-medium text-gray-700">圖片預覽：</p>
                    <img id="previewImg" src="#" alt="圖片預覽" class="h-auto max-w-full mt-2 rounded-md">
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" class="px-4 py-2 mr-2 text-gray-800 bg-gray-200 rounded-md" onclick="closeUploadModal()">取消</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md">上傳</button>
                </div>
            </form>
        </div>
    </div>
</section>

        <!-- 第二排：網站狀態 (25%) 和 快捷操作 (75%) -->
        <!-- 網站狀態 -->
        <section class="col-span-1 sm:col-span-3 p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow min-h-[200px] animate-fade-in" aria-label="網站狀態">
            <h3 class="text-lg font-semibold text-gray-800">網站狀態</h3>
            <div class="mt-2 space-y-2">
                <p class="text-sm text-gray-700" aria-live="polite">當前上線用戶：<span id="online-users">{{ is_numeric($onlineUsers) ? $onlineUsers : '載入中...' }}</span></p>
                <p class="flex items-center text-sm text-gray-700" aria-live="polite">
                    伺服器狀態：
                    <span class="inline-flex items-center ml-2">
                        <span id="status-indicator" class="w-2 h-2 mr-1 rounded-full {{ is_string($serverStatus) && $serverStatus === '正常' ? 'bg-green-500' : 'bg-red-500' }}" aria-label="伺服器狀態：{{ is_string($serverStatus) ? $serverStatus : '未知' }}"></span>
                        <span id="server-status">{{ is_string($serverStatus) ? $serverStatus : '未知' }}</span>
                    </span>
                </p>
            </div>
        </section>

        <!-- 快捷操作 -->
        <section class="col-span-1 sm:col-span-9 p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow min-h-[200px] animate-fade-in" aria-label="快捷操作">
            <h3 class="text-lg font-semibold text-gray-800">快速操作</h3>
            <p class="mt-2 text-sm text-gray-600">快速訪問常用功能</p>
            <div class="mt-4 space-y-2">
                <a href="#" class="block px-4 py-2 text-sm text-blue-600 rounded-md bg-blue-50 hover:bg-blue-100">管理員工</a>
                <a href="#" class="block px-4 py-2 text-sm text-blue-600 rounded-md bg-blue-50 hover:bg-blue-100">查看報表</a>
            </div>
        </section>
    </div>
</div>
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
#uploadModal {
        z-index: 1000;
    }
    .group:hover .opacity-0 {
        opacity: 1;
    }
</style>
<script>
    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
        document.getElementById('imageUpload').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
    }

    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && (file.type === 'image/png' || file.type === 'image/jpeg')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            alert('請選擇 PNG 或 JPEG 格式的圖片');
            event.target.value = '';
        }
    });
</script>
