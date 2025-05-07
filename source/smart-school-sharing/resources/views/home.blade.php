@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Left Ad Section -->
        <div class="flex-1">
            <!-- Search Section -->
            <section class="p-8 text-center">
                <h2 class="text-3xl font-bold text-green-700">Give & Get Smart – Share Your Unused School Supplies!</h2>
                <div class="mt-4">
                    <form method="GET" action="{{ route('items.search') }}" class="flex justify-center max-w-3xl mx-auto">
                        <input type="text" name="query" placeholder="Search for supplies..."
                               class="border border-gray-300 rounded-l-md px-4 py-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-green-600"
                               value="{{ request('query') ?? '' }}">
                        <button class="bg-green-600 text-white px-6 py-2 rounded-r-md hover:bg-green-700 transition" type="submit">
                            Search
                        </button>
                    </form>
                </div>
            </section>

            <!-- Search Results Section -->
            @if(isset($searchResults) && $searchResults->count() > 0)
                <section class="p-4 bg-white">
                    @if(isset($searchResults) && $searchResults->count() > 0)
                        <section class="p-4 bg-white">
                            <h2 class="text-2xl font-bold mb-6 text-green-700 pl-4">
                                @if(request()->has('category'))
                                    {{ $categoryName ?? 'Category' }} Items
                                @else
                                    Search Results for "{{ request('query') }}"
                                @endif
                            </h2>
                            <!-- ... phần còn lại giữ nguyên ... -->
                        </section>
                    @endif
                    <div class="space-y-4">
                        @foreach ($searchResults as $item)
                            <div class="bg-white border rounded-lg overflow-hidden hover:shadow-md transition flex flex-col md:flex-row min-w-0">
                                <!-- Tách riêng phần clickable và button -->
                                <div class="flex flex-col md:flex-row">
                                    <!-- Phần thông tin có thể click -->
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
                    @if($searchResults->hasPages())
                        <div class="mt-6">
                            {{ $searchResults->appends(request()->query())->links() }}
                        </div>
                    @endif
                </section>
            @elseif(request()->has('query'))
                <section class="p-8 bg-white text-center">
                    <p class="text-gray-600">No items found for "{{ request('query') }}"</p>
                    <a href="{{ route('home') }}" class="text-green-600 hover:underline mt-2 inline-block">
                        Back to home
                    </a>
                </section>
            @endif

            <!-- Featured Items Section (Chỉ hiển thị khi không có tìm kiếm) -->
            @if(!request()->has('query'))
                <section class="p-8 bg-yellow-100">
                    <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Featured Items</h2>
                    <!-- Swiper Container -->
                    <!-- Swiper -->
                    <div class="relative">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper" id="featured-items-wrapper">
                                @foreach ($featuredItems as $item)
                                    <div class="swiper-slide h-auto">
{{--                                        <a href="{{ route('items.show', $item->id) }}" class="slider-item min-w-[200px] bg-white p-4 shadow rounded-md block hover:bg-green-50 transition">--}}
{{--                                            @if($item->first_image_url)--}}
{{--                                                <img src="{{ $item->first_image_url }}"--}}
{{--                                                     alt="{{ $item->name }}"--}}
{{--                                                     class="w-full h-40 object-cover rounded-t-md mb-3">--}}
{{--                                            @else--}}
{{--                                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded-t-md mb-3">--}}
{{--                                                    <span class="text-gray-500 text-sm">No Image</span>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}

{{--                                            <div class="font-bold text-lg text-green-700">{{ $item->name }}</div>--}}
{{--                                            <div class="text-gray-600 text-sm">{{ Str::limit($item->description, 60) }}</div>--}}
{{--                                            <div class="text-xs mt-2 text-gray-400">#{{ $item->item_condition }} | {{ $item->status }}</div>--}}
{{--                                        </a>--}}
                                        <a href="{{ route('items.show', $item->id) }}" class="slider-item bg-white shadow rounded-md hover:bg-green-50 transition">
                                            @if($item->first_image_url)
                                                <img src="{{ $item->first_image_url }}"
                                                     alt="{{ $item->name }}"
                                                     class="w-full rounded-t-md">
                                            @else
                                                <div class="w-full bg-gray-200 flex items-center justify-center rounded-t-md">
                                                    <span class="text-gray-500 text-sm">No Image</span>
                                                </div>
                                            @endif

                                            <div class="slider-content">
                                                <div class="item-name">{{ $item->name }}</div>
                                                <div class="item-description" title="{{ $item->description }}">
                                                    {{ $item->description }}
                                                </div>
                                                <div class="item-meta">
                                                    #{{ $item->item_condition }} | {{ $item->status }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Navigation buttons (để BÊN NGOÀI swiper-wrapper) -->
                        <div class="swiper-button-prev custom-nav"></div>
                        <div class="swiper-button-next custom-nav"></div>
                    </div>

                </section>
                <!-- Categories Section -->
                <section class="p-8">
                    <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Popular Categories</h2>
                    <div class="category-grid">
                        <a href="{{ route('home', ['category' => 1]) }}" class="category-card">📚 Books</a>
                        <a href="{{ route('home', ['category' => 2]) }}" class="category-card">✏️ Stationery</a>
                        <a href="{{ route('home', ['category' => 3]) }}" class="category-card">💻 Gadgets</a>
                        <a href="{{ route('home', ['category' => 4]) }}" class="category-card">🎒 Backpacks</a>
                        <a href="{{ route('home', ['category' => 5]) }}" class="category-card">🎨 Art Supplies</a>
                        <a href="{{ route('home', ['category' => 6]) }}" class="category-card">📝 Notebooks</a>
                    </div>
                </section>
            @endif
        </div>

        <!-- Right Ad Section -->
    </div>

    <!-- Include Borrow Request Modal -->
    @include('components.borrow-request-modal')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            let lastPage = false;

            const swiper = new Swiper(".mySwiper", {
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                loop: false,
                watchOverflow: false, // ép phải hiện nút Next Prev dù ít slide
                breakpoints: {
                    640: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 4 },
                },
                on: {
                    reachEnd: function () {
                        if (!lastPage) {
                            loadMoreItems();
                        }
                    }
                }
            });

            function loadMoreItems() {
                currentPage++;
                fetch(`{{ route('api.featured-items') }}?page=${currentPage}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.data.length === 0 || data.current_page >= data.last_page) {
                            lastPage = true;
                            hideNextButton();
                        }

                        // Đoạn code mới thêm vào đây
                        data.data.forEach(item => {
                            swiper.appendSlide(`
                        <div class="swiper-slide h-auto">
                            <a href="/items/${item.id}" class="slider-item bg-white shadow rounded-md hover:bg-green-50 transition">
                                ${item.first_image_url ? `
                                    <img src="${item.first_image_url}"
                                         alt="${item.name}"
                                         class="w-full rounded-t-md">
                                ` : `
                                    <div class="w-full bg-gray-200 flex items-center justify-center rounded-t-md">
                                        <span class="text-gray-500 text-sm">No Image</span>
                                    </div>
                                `}
                                <div class="slider-content">
                                    <div class="item-name">${item.name}</div>
                                    <div class="item-description" title="${item.description || ''}">
                                        ${item.description || ''}
                                    </div>
                                    <div class="item-meta">
                                        #${item.item_condition ?? ''} | ${item.status ?? ''}
                                    </div>
                                </div>
                            </a>
                        </div>
                        `);
                        });
                    })
                    .catch(error => console.error('Error loading featured items:', error));
            }

            function hideNextButton() {
                document.querySelector('.swiper-button-next').style.display = 'none';
            }
        });
    </script>
    <style>
        .custom-nav {
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .swiper-button-prev.custom-nav::after,
        .swiper-button-next.custom-nav::after {
            font-size: 18px;
            font-weight: bold;
        }

        .swiper-button-prev.custom-nav {
            left: -20px; /* canh ra bên trái */
        }

        .swiper-button-next.custom-nav {
            right: -20px; /* canh ra bên phải */
        }

        .slider-item {
            height: 100%; /* Chiếm full height của slide */
            display: flex;
            flex-direction: column;
        }

        .slider-item img, .slider-item div[class*="bg-gray-200"] {
            height: 160px; /* Fixed height cho ảnh */
            object-fit: cover;
        }

        .slider-content {
            flex: 1; /* Chiếm phần không gian còn lại */
            display: flex;
            flex-direction: column;
            padding: 12px;
        }

        .item-name {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            font-size: 1.125rem;
            font-weight: bold;
            color: #047857; /* Màu green-700 */
            margin-bottom: 4px;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .item-description {
            display: -webkit-box;
            -webkit-line-clamp: 1; /* Giới hạn 1 dòng */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #4b5563; /* Màu gray-600 */
            font-size: 0.875rem;
            line-height: 1.25rem;
            margin-bottom: 8px;
            min-height: 1.25rem; /* Đảm bảo có space ngay cả khi không có description */
        }

        .item-meta {
            font-size: 0.75rem;
            color: #9ca3af; /* Màu gray-400 */
            margin-top: auto; /* Đẩy xuống dưới cùng */
        }
    </style>


@endsection
