{{-- resources/views/components/items-list.blade.php --}}
@props([
    'items',
    'title',
    'emptyMessage' => 'No items found',
    'requestCount' => null,
    'userRequests' => null
])

<section class="p-4 bg-white">
    <h2 class="text-2xl font-bold mb-6 text-green-700 pl-4">{{ $title }}</h2>

    @if($items->isEmpty())
        <div class="p-8 bg-white text-center">
            <p class="text-gray-600">{{ $emptyMessage }}</p>
            <a href="{{ route('home') }}" class="text-green-600 hover:underline mt-2 inline-block">
                Back to home
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($items as $item)
                <div class="bg-white border rounded-lg overflow-hidden hover:shadow-md transition flex flex-col md:flex-row min-w-0">
                    <div class="flex flex-col md:flex-row">
                        <!-- Clickable Info -->
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
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-bold text-green-700">{{ $item->name }}</h3>
                                        <p class="text-gray-500 text-sm mt-1">
                                            {{ $item->created_at->format('Y') }} -
                                            {{ ucfirst($item->item_condition) }} -
                                            Used
                                        </p>
                                    </div>
                                </div>

                                <p class="text-gray-600 mt-2">{{ Str::limit($item->description, 100) }}</p>

                                <div class="flex items-center mt-3 text-sm text-gray-500">
                                    <span class="mr-2">📍 {{ $item->location ?? 'N/A' }}</span>
                                    <span class="px-2 py-1 rounded
                                        @if($item->status === 'available') bg-green-100 text-green-800
                                        @elseif($item->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center mt-4">
                                    <div class="flex items-center">
                                        <div class="bg-gray-100 rounded-full p-1 mr-2">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">{{ $item->user->name ?? 'Unknown' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Borrow Button -->
                        <div class="p-4 flex items-center justify-end md:justify-center w-48 flex-shrink-0">
                            <x-borrow-button
                                :item="$item"
                                :requestCount="$requestCount"
                                :userRequests="$userRequests"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        @endif
    @endif
</section>
