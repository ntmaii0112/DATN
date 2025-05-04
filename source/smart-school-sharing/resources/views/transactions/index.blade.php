@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px space-x-8">
                        <a href="{{ route('transactions.index') }}"
                           class="{{ request()->tab !== 'requests' && request()->tab !== 'posts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors duration-200">
                            Your Transactions
                        </a>
                        <a href="{{ route('transactions.index', ['tab' => 'requests']) }}"
                           class="{{ request()->tab === 'requests' && request()->tab !== 'posts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors duration-200">
                            My Requests
                        </a>
                        <a href="{{ route('transactions.index', ['tab' => 'posts']) }}"
                           class="{{ request()->tab === 'posts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors duration-200">
                            My Posts
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            @if(request()->tab === 'requests')
                My Item Requests
            @elseif(request()->tab === 'posts')
                My Posts
            @else
                Your Transactions
            @endif
        </h1>
        @if(request()->tab === 'posts')
            <!-- Posts Tab Content -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($item->first_image_url)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $item->first_image_url }}" alt="{{ $item->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->category->name ?? 'N/A' }}
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
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->created_at->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($item->del_flag == 0)
                                        <a href="{{ route('items.show', $item->id) }}"
                                           class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        @if($item->status === 'submit')
                                            <a href="{{ route('items.edit', $item->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    You haven't posted any items yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($items->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Other Party</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <!-- Item Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($transaction->item->first_image_url)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $transaction->item->first_image_url }}" alt="{{ $transaction->item->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->item->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->item->category->name }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Type Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->giver_id == auth()->id())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        You gave
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        You requested
                                    </span>
                                @endif
                            </td>

                            <!-- Other Party Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($transaction->giver_id == auth()->id())
                                        {{ $transaction->receiver->name }}
                                    @else
                                        {{ $transaction->giver->name }}
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($transaction->giver_id == auth()->id())
                                        {{ $transaction->borrower_name ?? 'N/A' }}
                                    @endif
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$transaction->request_status] }}">
                                    {{ ucfirst($transaction->request_status) }}
                                </span>
                                @if($transaction->request_status == 'rejected' && $transaction->rejection_reason)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Reason: {{ $transaction->rejection_reason }}
                                    </div>
                                @endif
                            </td>

                            <!-- Dates Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($transaction->start_date && $transaction->end_date)
                                    {{ $transaction->start_date->format('M d') }} - {{ $transaction->end_date->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>

                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('transactions.show', $transaction->id) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3">View</a>

                                @if($transaction->giver_id == auth()->id() && $transaction->request_status == 'pending')
                                    <!-- Approve/Reject buttons for owner -->
                                    <form action="{{ route('transactions.approve', $transaction->id) }}"
                                          method="POST" class="inline-block mr-2">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Are you sure you want to approve this request?')">
                                            Approve
                                        </button>
                                    </form>

                                    <div x-data="{ showRejectModal: false, rejectionReason: '' }" class="inline-block">
                                        <button @click="showRejectModal = true"
                                                class="text-red-600 hover:text-red-900">
                                            Reject
                                        </button>

                                        <!-- Reject Modal -->
                                        <div x-show="showRejectModal"
                                             x-transition
                                             class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                                            <div @click.away="showRejectModal = false"
                                                 class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                                                <h3 class="text-lg font-medium mb-4">Reject Request</h3>

                                                <form action="{{ route('transactions.reject', $transaction->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">
                                                            Reason (optional)
                                                        </label>
                                                        <textarea id="rejection_reason" name="rejection_reason"
                                                                  x-model="rejectionReason"
                                                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                                    </div>

                                                    <div class="flex justify-end space-x-3">
                                                        <button @click="showRejectModal = false"
                                                                type="button"
                                                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Cancel
                                                        </button>
                                                        <button type="submit"
                                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            Confirm Rejection
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($transaction->receiver_id == auth()->id() && $transaction->request_status == 'pending')
                                    <!-- Cancel button for requester -->
                                    <form action="{{ route('transactions.cancel', $transaction) }}"
                                          method="POST"
                                          class="inline-block ml-2"
                                          x-data="{ confirming: false }">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                @click="confirming = true"
                                                class="text-red-600 hover:text-red-900">
                                            Cancel
                                        </button>

                                        <!-- Confirmation Modal -->
                                        <div x-show="confirming"
                                             x-transition
                                             class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                                            <div @click.away="confirming = false"
                                                 class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                                                <h3 class="text-lg font-medium mb-4">Confirm Cancellation</h3>
                                                <p class="mb-4">Are you sure you want to cancel this request?</p>

                                                <div class="flex justify-end space-x-3">
                                                    <button @click="confirming = false"
                                                            type="button"
                                                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        No, keep it
                                                    </button>
                                                    <button type="submit"
                                                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        Yes, cancel request
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                @if(request()->is('transactions/requests'))
                                    You haven't made any requests yet.
                                @else
                                    No transactions found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
        @endif
    </div>
@endsection
