@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-8 bg-white shadow rounded-md flex flex-col md:flex-row gap-8">
        <!-- Image Gallery Section -->
        <div class="w-full md:w-1/2"
             x-data="{
                 current: 0,
                 images: {{ json_encode($images ?? []) }},
                 next() {
                     this.current = (this.current + 1) % this.images.length;
                 },
                 prev() {
                     this.current = (this.current - 1 + this.images.length) % this.images.length;
                 }
             }"
             x-init="console.log('Images loaded:', images)">

            <div class="relative overflow-hidden rounded-md border">
                <!-- Main Image Display -->
                <template x-if="images.length > 0">
                    <img x-bind:src="images[current]"
                         class="w-full h-64 md:h-96 object-cover transition-all duration-300"
                         alt="Item image">
                </template>

                <!-- Placeholder when no images -->
                <template x-if="images.length === 0">
                    <div class="w-full h-64 md:h-96 flex items-center justify-center bg-gray-100 text-gray-500">
                        No Image Available
                    </div>
                </template>

                <!-- Navigation Buttons -->
                <button x-show="images.length > 1" @click="prev()"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 px-3 py-1 bg-white bg-opacity-75 hover:bg-opacity-100 rounded-full shadow-md">
                    ‹
                </button>
                <button x-show="images.length > 1" @click="next()"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 px-3 py-1 bg-white bg-opacity-75 hover:bg-opacity-100 rounded-full shadow-md">
                    ›
                </button>
            </div>

            <!-- Thumbnail Indicators -->
            <div class="flex justify-center mt-4 space-x-2" x-show="images.length > 1">
                <template x-for="(image, index) in images" :key="index">
                    <button @click="current = index"
                            :class="{'bg-green-600': current === index, 'bg-gray-300': current !== index}"
                            class="w-3 h-3 rounded-full transition-colors duration-200"></button>
                </template>
            </div>
        </div>

        <!-- Item Details Section -->
        <div class="w-full md:w-1/2">
            <!-- Back button -->
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('admin.items.index') }}"
                   class="inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors text-sm">
                    ← Back to List
                </a>


            </div>

            <h1 class="text-3xl font-bold text-green-700 mb-4 truncate" title="{{ $item->name }}">
                {{ $item->name }}
            </h1>

            <!-- Status Badge -->
            <div class="mb-4">
                @if($item->del_flag)
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-200 text-gray-800">Deleted</span>
                @else
                    @php
                        $statusClasses = [
                            'submit' => 'bg-yellow-100 text-yellow-800',
                            'available' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'borrowed' => 'bg-blue-100 text-blue-800'
                        ];
                        $defaultClass = 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$item->status] ?? $defaultClass }}">
                        {{ ucfirst($item->status) }}
                    </span>
                @endif
            </div>

            <p class="text-gray-600 mb-6 whitespace-pre-line break-words">
                {{ $item->description }}
            </p>

            <div class="space-y-3 mb-6">
                <div class="flex items-center">
                    <span class="w-24 font-semibold">Category:</span>
                    <span>{{ $item->category->name ?? 'N/A' }}</span>
                </div>

                <div class="flex items-center">
                    <span class="w-24 font-semibold">Condition:</span>
                    <span>{{ ucfirst($item->item_condition) }}</span>
                </div>

                <div class="flex items-center">
                    <span class="w-24 font-semibold">Deposit:</span>
                    <span>{{ number_format($item->deposit_amount) }} VND</span>
                </div>

                <div class="flex items-center">
                    <span class="w-24 font-semibold">Owner:</span>
                    @if($item->user)
                        <a href="{{ route('users.show', $item->user->id) }}" class="text-green-700 hover:underline">
                            {{ $item->user->name }}
                        </a>
                    @else
                        <span>Unknown</span>
                    @endif
                </div>

                @if($item->status === 'rejected' && $rejectionReason)
                    <div class="flex items-start">
                        <span class="w-24 font-semibold">Rejection Reason:</span>
                        <span class="text-red-600">{{ $rejectionReason->reason }}</span>
                    </div>
                @endif
            </div>

            <div class="text-sm text-gray-500 mb-6">
                <span class="font-semibold">Created at:</span> {{ $item->created_at->format('d-m-Y H:i') }}
                <br>
                <span class="font-semibold">Updated at:</span> {{ $item->updated_at->format('d-m-Y H:i') }}
            </div>

            <!-- Admin Action Buttons moved here -->
            @if(!$item->del_flag)
                <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-200">
                    @if($item->status === 'submit')
                        <form method="POST" action="{{ route('admin.items.approve', $item) }}">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                Approve Item
                            </button>
                        </form>

                        <button onclick="openRejectModal({{ $item->id }})"
                                class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
                            Reject Item
                        </button>
                    @endif
                        @if(!$item->del_flag && $item->status != 'borrowed')
                            <form method="POST" action="{{ route('admin.items.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-block px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition-colors text-sm"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                    Delete Item
                                </button>
                            </form>
                        @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4 text-red-600">Reject Item</h2>
            <form method="POST" id="rejectForm">
                @csrf
                <input type="hidden" name="item_id" id="rejectItemId">
                <label class="block mb-2 font-medium">Reason for rejection:</label>
                <textarea name="reason" required rows="4"
                          class="w-full border border-gray-300 rounded p-2 mb-4"></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .item-name {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.3s ease;
            cursor: default;
            position: relative;
            z-index: 0;
        }
        .item-name:hover {
            -webkit-line-clamp: unset;
            white-space: normal;
            overflow: visible;
            background-color: #f9f9f9;
            z-index: 10;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function imageGallery() {
            return {
                current: 0,
                images: @json($images ?? []),
                init() {
                    console.log('Gallery initialized with images:', this.images);
                },
                next() {
                    this.current = (this.current + 1) % this.images.length;
                },
                prev() {
                    this.current = (this.current - 1 + this.images.length) % this.images.length;
                }
            }
        }

        function openRejectModal(itemId) {
            document.getElementById('rejectItemId').value = itemId;
            const form = document.getElementById('rejectForm');
            form.action = `/admin/items/${itemId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endpush
