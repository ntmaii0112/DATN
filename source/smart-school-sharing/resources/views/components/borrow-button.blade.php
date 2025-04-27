@props([
    'item',
    'requestCount' => 0,
    'userRequests' => null,
    'requestLimit' => null
])

@php
    // Sửa cách kiểm tra already_requested
    $requestLimit = $requestLimit ?? config('borrow.limits.max_requests_per_user');
    $alreadyRequested = $userRequests->contains('item_id', $item->id);
    $limitExceeded = $requestCount >= $requestLimit;

    // Lấy trạng thái nếu đã yêu cầu
    $requestStatus = $alreadyRequested
        ? $userRequests->firstWhere('item_id', $item->id)->status
        : null;
@endphp

<div class="p-4 flex items-center justify-end">
    @auth
        @if($limitExceeded && !$alreadyRequested)
            <button onclick="showRequestLimitAlert({{ $requestCount }})"
                    class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded cursor-not-allowed"
                    title="Bạn đã gửi quá {{ $requestLimit }} yêu cầu">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Đạt giới hạn
            </button>
        @elseif(!$alreadyRequested && $item->status === 'available')
            <button onclick="openModal({{ $item->id }}, '{{ $item->name }}')"
                    class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800 hover:bg-blue-200 transition whitespace-nowrap">
                Gửi yêu cầu mượn
            </button>
        @elseif($alreadyRequested)
            <span class="status-badge bg-yellow-100 text-yellow-800">
        <i class="fas fa-clock"></i>
        <span class="truncate">Đang chờ ({{ $userRequests->where('item_id', $item->id)->first()->status ?? 'pending' }})</span>
    </span>
        @else
            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded">
                {{ ucfirst($item->status) }}
            </span>
        @endif
    @else
        <a href="{{ route('login') }}"
           class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            Đăng nhập để mượn
        </a>
    @endauth
</div>
