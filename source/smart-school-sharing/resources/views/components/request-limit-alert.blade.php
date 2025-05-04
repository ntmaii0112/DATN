@props([
    'requestCount' => 0,
    'requestLimit' => 10
])

<div id="requestLimitAlert" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-red-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Request Limit Reached
            </h3>
            <button onclick="closeAlert()" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>
        <p class="mb-4">
            You have submitted? <span class="font-bold">{{ $requestCount }}/{{ $requestLimit }}</span> Borrow request submitted. Please wait for approval or cancel some requests before submitting more.
        </p>
        <div class="mt-4">
            <a href="{{ route('user.requests') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mr-2">
                View My Requests
            </a>
            <button onclick="closeAlert()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Close
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
