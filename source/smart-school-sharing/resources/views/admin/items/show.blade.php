@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-green-700">Item Details</h2>
            <a href="{{ route('admin.items.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to List
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Basic Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="mt-1 text-gray-900">{{ $item->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $item->description }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Category</p>
                                <p class="mt-1 text-gray-900">{{ $item->category->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Condition</p>
                                <p class="mt-1 text-gray-900">{{ $item->item_condition }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status and Owner Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Status Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Condition</p>
                                <p class="mt-1 text-gray-900">{{ $item->item_condition }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Deposit Amount</p>
                                <p class="mt-1 text-gray-900">{{ number_format($item->deposit_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                @if($item->del_flag)
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 text-gray-700">Deleted</span>
                                @else
                                    @php
                                        $statusClasses = [
                                            'submit' => 'bg-yellow-100 text-yellow-800',
                                            'available' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800'
                                        ];
                                        $defaultClass = 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusClasses[$item->status] ?? $defaultClass }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </div>

                            @if($item->status === 'rejected' && $rejectionReason)
                                <div>
                                    <p class="text-sm text-gray-500">Rejection Reason</p>
                                    <p class="mt-1 text-gray-900">{{ $rejectionReason->reason }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm text-gray-500">Owner</p>
                                <p class="mt-1 text-gray-900">{{ $item->user->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Created At</p>
                                <p class="mt-1 text-gray-900">{{ $item->created_at->format('M d, Y H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="mt-1 text-gray-900">{{ $item->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-3">
                        @if(!$item->del_flag)
                            @if($item->status === 'submit')
                                <form method="POST" action="{{ route('admin.items.approve', $item) }}">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Approve
                                    </button>
                                </form>

                                <form onsubmit="openRejectModal({{ $item->id }}); return false;">
                                    <button type="submit"
                                            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                                        Reject
                                    </button>
                                </form>
                            @endif
                        @endif

                        @if(!$item->del_flag && $item->status != 'borrowed')
                            <form method="POST" action="{{ route('admin.items.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal (same as in index.blade.php) -->
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

    <script>
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
@endsection
