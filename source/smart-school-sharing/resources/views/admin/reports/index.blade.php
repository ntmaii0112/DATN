@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-green-700">User Reports</h2>

            <form method="GET" action="{{ route('admin.reports.index') }}" class="w-full md:w-auto">
                <div class="flex">
                    <input type="text" name="search"
                           class="form-control px-4 py-2 border rounded-l-md w-full"
                           placeholder="Search by reporter..."
                           value="{{ old('search', request('search')) }}"
                           aria-label="Search reports">
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 border rounded-r-md hover:bg-green-700 transition-colors">
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

        @if($reports->isEmpty())
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg">
                No user reports found.
            </div>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reports as $report)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $report->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $report->reporter->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $report->reported->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words max-w-xs">
                                {{ $report->reason ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'resolved' => 'bg-green-100 text-green-800',
                                    ];
                                    $defaultClass = 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusClasses[$report->status] ?? $defaultClass }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('admin.reports.show', $report) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
@endsection
