@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-red-700">Report Details #{{ $report->id }}</h2>
            <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Reporter Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Reporter Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="mt-1 text-gray-900">{{ $report->reporter->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="mt-1 text-gray-900">{{ $report->reporter->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Reported At</p>
                                <p class="mt-1 text-gray-900">{{ $report->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reported User -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Reported User</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="mt-1 text-gray-900">{{ $report->reported->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="mt-1 text-gray-900">{{ $report->reported->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                @php
                                    $statusClass = $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Reason -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800">Report Reason</h3>
                    <div class="bg-gray-100 rounded p-4 text-gray-900 break-words max-w-xs">
                        {{ $report->reason }}
                    </div>
                </div>

                <!-- Resolve Button -->
                @if($report->status === 'pending')
                    <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Mark as Resolved
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
