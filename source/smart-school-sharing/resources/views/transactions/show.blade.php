@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Transaction Detail</h2>

            <!-- Transaction Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Transaction Information</h3>
                <ul class="list-none">
                    <li><strong>Transaction ID:</strong> {{ $transaction->id }}</li>
                    <li><strong>Giver:</strong> {{ $giver->name }}</li>
                    <li><strong>Receiver:</strong> {{ $receiver->name }} </li>
                </ul>
            </div>

            <!-- Item Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Item Information</h3>
                <ul class="list-none">
                    <li><strong>Item Name:</strong> {{ $item->name }}</li>
                    <li><strong>Item Category:</strong> {{ $item->category->name ?? 'N/A' }}</li>
                    <li><strong>Item Status:</strong> {{ ucfirst($item->status) }}</li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Contact Information</h3>
                <ul class="list-none">
                    <li><strong>Start Date:</strong> {{ $transaction->start_date }}</li>
                    <li><strong>End Date:</strong> {{ $transaction->end_date }}</li>
                    <li><strong>Purpose:</strong> {{ $transaction->purpose }}</li>
                    <li><strong>Message:</strong> {{ $transaction->message }}</li>
                    <li><strong>Request Status:</strong>
                        <span class="text-{{ $transaction->request_status == 'approved' ? 'green' : 'red' }}-600">
                            {{ ucfirst($transaction->request_status) }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end mt-4">
                @if($transaction->status == 'approved')
                    <form action="{{ route('transactions.reject', $transaction->id) }}" method="POST" class="mr-3">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">
                            Reject
                        </button>
                    </form>
                @endif

                <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md">
                    Back to Transactions
                </a>
            </div>
        </div>
    </div>
@endsection
