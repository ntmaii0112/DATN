@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-green-700">Contact Messages</h2>

            <form method="GET" action="{{ route('admin.contacts.index') }}" class="w-full md:w-auto">
                <div class="flex">
                    <input type="text" name="search"
                           class="form-control px-4 py-2 border rounded-l-md w-full"
                           placeholder="Search by name or email..."
                           value="{{ request('search') }}"
                           aria-label="Search contacts">
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

        @if($contacts->isEmpty())
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg">
                No contact messages found.
            </div>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($contacts as $contact)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $contact->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $contact->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $contact->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 break-words whitespace-normal max-w-xs">
                                {{ $contact->message }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.contacts.show', $contact) }}"
                                       class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                        View
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
@endsection
