@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8" x-data="{ openReportModal: false }">
        <h1 class="text-2xl font-bold mb-4">Thông tin người dùng</h1>

        <!-- User Information Section -->
        <div class="bg-white shadow p-4 rounded mb-6">
            <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>SĐT:</strong> {{ $user->phone ?? 'N/A' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'N/A' }}</p>

            @auth
                @if(Auth::id() !== $user->id)
                    <button @click="openReportModal = true"
                            class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                        Report User
                    </button>
                @endif
            @endauth
        </div>

        <!-- Items Shared by User Section -->
        <section class="p-8 bg-white rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-6 text-green-700">Items Shared by {{ $user->name }}</h2>

            @if ($user->items->count())
                <div class="space-y-4">
                    @foreach ($user->items as $item)
                        <div class="bg-white border rounded-lg overflow-hidden hover:shadow-md transition flex flex-col md:flex-row">
                            <a href="{{ route('items.show', $item->id) }}" class="flex flex-1">
                                <div class="md:w-1/4 flex-shrink-0">
                                    @if($item->first_image_url)
                                        <img src="{{ $item->first_image_url }}" alt="{{ $item->name }}"
                                             class="w-full h-48 md:h-full object-cover">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="No Image"
                                             class="w-full h-48 md:h-full object-cover">
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0 p-4">
                                    <h3 class="text-lg font-bold text-green-700">{{ $item->name }}</h3>
                                    <p class="text-gray-500 text-sm mt-1">
                                        {{ $item->created_at->format('Y-m-d') }} -
                                        {{ ucfirst($item->item_condition) }} -
                                        {{ ucfirst($item->status) }}
                                    </p>
                                    <p class="text-gray-600 mt-2">{{ Str::limit($item->description, 100) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">This user hasn't shared any items yet.</p>
            @endif
        </section>

        <!-- Report Modal -->
        <div x-show="openReportModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             style="display: none;"> <!-- Initial hidden state -->

            <div @click.away="openReportModal = false"
                 class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4">Report User: {{ $user->name }}</h2>

                    <form action="{{ route('users.report', $user->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                                Reason for reporting:
                            </label>
                            <textarea id="reason" name="reason" required rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="openReportModal = false"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
