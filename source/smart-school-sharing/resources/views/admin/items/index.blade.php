@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-green-700">Items Pending Approval</h2>

            <form method="GET" action="{{ route('admin.items.index') }}" class="w-full md:w-auto">
                <div class="flex">
                    <input type="text" name="search"
                           class="form-control px-4 py-2 border rounded-l-md w-full"
                           placeholder="Search by name..."
                           value="{{ old('search', request('search')) }}"
                           aria-label="Search items">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 border rounded-r-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-search"></i>
                        <span class="sr-only">Search</span>
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($items->isEmpty())
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg">
                No items to approve.
            </div>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                        <tr class="{{ $item->del_flag ? 'bg-gray-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">
                                {{ $item->id }}
                            </td>
{{--                            <td class="px-6 py-4 text-sm break-words whitespace-normal max-w-xs {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">--}}
{{--                                {{ $item->name }}--}}
{{--                            </td>--}}
                            <td class="px-6 py-4 text-sm break-words whitespace-normal max-w-xs {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">
                                <a href="{{ route('admin.items.show', $item) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">
                                {{ $item->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">
                                {{ $item->category->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">
                                {{ $item->item_condition }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->del_flag ? 'text-gray-400' : 'text-gray-900' }}">
                                {{ number_format($item->deposit_amount, 2) }} (VND)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if(!$item->del_flag)
                                        @if($item->status === 'submit')
                                            <form method="POST" action="{{ route('admin.items.approve', $item) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                                    Approve
                                                </button>
                                            </form>
                                            <!-- Reject button with modal trigger -->
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
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>
@endsection
<!-- Modal -->
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
        form.action = `/admin/items/${itemId}/reject`; // Route giả lập
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('flex');
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>

