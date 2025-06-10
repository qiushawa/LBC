<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
    <div class="p-6 transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800">總員工數</h3>
        <span class="text-3xl font-bold text-blue-600">{{ $totalEmployees }}</span>/位
    </div>

</div>
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-semibold text-gray-800">員工列表</h3>
        <form method="GET" action="{{ route('admin.employees') }}"
            class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto">

            <div class="flex gap-2">
                <a href="{{ route('admin.employees.create') }}"
                    class="px-4 py-2 text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700">新增員工</a>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-sm font-medium text-gray-700">員工編號</th>
                    <th class="p-3 text-sm font-medium text-gray-700">姓名</th>
                    <th class="p-3 text-sm font-medium text-gray-700">Email</th>
                    <th class="p-3 text-sm font-medium text-gray-700">職位</th>
                    <th class="p-3 text-sm font-medium text-gray-700">權限等級</th>
                    <th class="p-3 text-sm font-medium text-gray-700">操作</th>
                </tr>
            </thead>
            <tbody id="employeeTable">
                @forelse ($employees as $employee)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm text-gray-600">{{ $employee->employee_id }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $employee->employee_name }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $employee->employee_email }}</td>
                        <td class="p-3 text-sm text-gray-600">{{ $employee->permission->job_title }}
                        </td>
                        <td class="p-3 text-sm text-gray-600">
                            {{ $employee->permission->permission_level }}</td>
                        <td class="p-3 text-sm">
                            <a href="{{ route('admin.employees.edit', $employee->employee_id) }}"
                                class="text-blue-600 hover:underline">編輯</a>
                            <form action="{{ route('admin.employees.delete', $employee->employee_id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('確定要刪除此員工？');"
                                    class="ml-2 text-red-600 hover:underline">刪除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-sm text-center text-gray-500">沒有找到符合條件的員工
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Links -->
    <div class="mt-4" id="paginationLinks">
        {{ $employees->appends(request()->query())->links()}}
    </div>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('loadingSpinner').classList.remove('hidden');
    });

    // Debounce search input
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.querySelector('form').submit();
        }, 500);
    });
</script>
