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
            <!-- Back to Home button moved to top -->
            <div class="mb-4">
                <a href="{{ route('home') }}"
                   class="inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors text-sm">
                    ← Back to Home
                </a>
            </div>
            <h1 class="text-3xl font-bold text-green-700 mb-4 truncate" title="{{ $item->name }}">
                {{ $item->name }}
            </h1>
            <p class="text-gray-600 mb-6 whitespace-pre-line break-words" title="{{ $item->description }}">
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
                    <span class="w-24 font-semibold">Status:</span>
                    <span class="px-2 py-1 rounded
                          @if($item->status === 'available') bg-green-100 text-green-800
                          @elseif($item->status === 'pending') bg-yellow-100 text-yellow-800
                          @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="w-24 font-semibold">Deposit:</span>
                    <span>{{ number_format($item->deposit_amount) }} VND</span>
                </div>

                <div class="flex items-center">
                    <span class="w-24 font-semibold">Shared by:</span>
                    @if($item->user)
                        <a href="{{ route('users.show', $item->user->id) }}" class="text-green-700 hover:underline">
                            {{ $item->user->name }}
                        </a>
                    @else
                        <span>Unknown</span>
                    @endif
                </div>
            </div>

            <div class="text-sm text-gray-500 mb-6">
                <span class="font-semibold">Created at:</span> {{ $item->created_at->format('d-m-Y H:i') }}
            </div>
            <!-- Gửi yêu cầu mượn -->
            <x-borrow-button
                :item="$item"
                :requestCount="$requestCount ?? 0"
                :userRequests="$userRequests ?? collect()"
            />
        </div>
    </div>
    <!-- Include Borrow Request Modal -->
    @include('components.borrow-request-modal')
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
                    // Debug log to check images are loaded
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
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUpload', () => ({
                previews: [],

                init() {
                    console.log('File upload component initialized');
                },

                handleFileSelect(event) {
                    const files = event.target.files;
                    if (!files || files.length === 0) return;

                    this.previews = [];

                    if (files.length > 5) {
                        alert('Maximum 5 images allowed');
                        event.target.value = '';
                        return;
                    }

                    Array.from(files).forEach(file => {
                        if (file.size > 2 * 1024 * 1024) {
                            alert(`File ${file.name} exceeds 2MB limit`);
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
                },

                removePreview(index) {
                    this.previews.splice(index, 1);
                    const input = this.$refs.fileInput;
                    if (!input || !input.files) return;

                    const remainingFiles = Array.from(input.files).filter((_, i) => i !== index);
                    const dataTransfer = new DataTransfer();

                    remainingFiles.forEach(file => {
                        dataTransfer.items.add(file);
                    });

                    input.files = dataTransfer.files;
                }
            }));
        });
    </script>

@endpush
