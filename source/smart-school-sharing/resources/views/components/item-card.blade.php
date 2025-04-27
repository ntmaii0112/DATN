{{-- resources/views/components/item-card.blade.php --}}
<div class="flex flex-col md:flex-row">
    <a href="{{ route('items.show', $item->id) }}" class="flex flex-1">
        <!-- Image Column -->
        <div class="md:w-1/4 flex-shrink-0">
            @if($item->first_image_url)
                <img src="{{ $item->first_image_url }}"
                     alt="{{ $item->name }}"
                     class="w-full h-48 md:h-full object-cover">
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="No Image" width="848px">
            @endif
        </div>

        <!-- Info Column -->
        <div class="flex-1 min-w-0 p-4">
            <!-- ... (giữ nguyên phần info như code cũ) ... -->
        </div>
    </a>

    <div class="p-4 flex items-center justify-end md:justify-center w-48 flex-shrink-0">
        <x-borrow-button
            :item="$item"
            :requestCount="$requestCount"
            :userRequests="$userRequests"
        />
    </div>
</div>
