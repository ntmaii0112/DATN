@props([
    'item',
    'requestCount' => 0,
    'userRequests' => null,
    'requestLimit' => null
])

@php
    $requestLimit = $requestLimit ?? config('borrow.limits.max_requests_per_user');
    $filteredRequests = $userRequests->filter(function ($request) use ($item) {
        return $request->item_id === $item->id &&
               in_array($request->request_status, ['pending','approved', 'completed']);
    });
    $alreadyRequested = $filteredRequests->isNotEmpty();
    $limitExceeded = $requestCount >= $requestLimit;

    $requestStatus = $alreadyRequested
        ? $userRequests->firstWhere('item_id', $item->id)->status
        : null;
@endphp

<div class="p-4 flex items-center justify-end">
    @auth
        @if($item->user_id != auth()->id())  <!-- Kiểm tra nếu item không phải của người dùng hiện tại -->
        @if($limitExceeded && !$alreadyRequested)
            <button onclick="showRequestLimitAlert({{ $requestCount }})"
                    class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded cursor-not-allowed"
                    title="You have exceeded the {{ $requestLimit }} request limit.">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Limit reached
            </button>
        @elseif(!$alreadyRequested && $item->status === 'available')
            <button onclick="openModal({{ $item->id }}, '{{ $item->name }}')"
                    class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800 hover:bg-blue-200 transition whitespace-nowrap">
                Send a borrow request
            </button>
        @elseif($alreadyRequested)
            <span class="status-badge bg-yellow-100 text-yellow-800">
            <i class="fas fa-clock"></i>
            <span class="truncate">borrowed</span>
        </span>
        @else
            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded">
        </span>
        @endif
        @else
            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded">
    </span>
        @endif
    @else
        <a href="{{ route('login') }}"
           class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            Please log in to borrow
        </a>
    @endauth
</div>
