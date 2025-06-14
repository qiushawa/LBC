
@props(['progress_list' => [], 'current_step' => 0])

<nav class="mb-8">
    {{-- <ol class="flex items-center justify-center space-x-4 text-sm font-medium">
        <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step active">
            <span>1. 選擇商品</span>
        </li>
        <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step">
            <span>2. 確認配置</span>
        </li>
        <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step">
            <span>3. 完成訂單</span>
        </li>
    </ol> --}}
    <ol class="flex items-center justify-center space-x-4 text-sm font-medium">
        @foreach ($progress_list as $index => $step)
            <li class="flex items-center px-4 py-2 bg-gray-200 rounded-full progress-step {{ $current_step == $index ? 'active' : '' }}">
                <span>{{ $index + 1 }}. {{ $step }}</span>
            </li>
        @endforeach
        </ol>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const steps = document.querySelectorAll('.progress-step');
        steps.forEach((step, index) => {
            if (index < {{ $current_step }}) {
                step.classList.add('bg-blue-500', 'text-white');
            } else if (index === {{ $current_step }}) {
                step.classList.add('bg-blue-300', 'text-white');
            } else {
                step.classList.add('bg-gray-200', 'text-gray-600');
            }
        });
    });
</script>
<style>
    .progress-step {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .progress-step.active {
        background-color: #3b82f6; /* Blue-500 */
        color: white;
    }
    .progress-step:not(.active) {
        background-color: #e5e7eb; /* Gray-200 */
        color: #4b5563; /* Gray-600 */
    }
    .progress-step:hover {
        background-color: #60a5fa; /* Blue-300 */
        color: white;
    }
</style>
