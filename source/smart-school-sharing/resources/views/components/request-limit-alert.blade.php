@props([
    'requestCount' => 0,
    'requestLimit' => 10
])

<div id="requestLimitAlert" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-red-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Đạt giới hạn yêu cầu
            </h3>
            <button onclick="closeAlert()" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>
        <p class="mb-4">
            Bạn đã gửi <span class="font-bold">{{ $requestCount }}/{{ $requestLimit }}</span> yêu cầu mượn.
            Vui lòng chờ phê duyệt hoặc hủy bớt yêu cầu trước khi gửi thêm.
        </p>
        <div class="mt-4">
            <a href="{{ route('user.requests') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mr-2">
                Xem yêu cầu của tôi
            </a>
            <button onclick="closeAlert()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Đóng
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function showRequestLimitAlert(count) {
            document.getElementById('requestLimitAlert').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAlert() {
            document.getElementById('requestLimitAlert').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endpush
