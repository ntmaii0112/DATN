@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-green-700">Account Management</h2>

            <div class="flex">
                <form method="GET" action="{{ route('admin.accounts.index') }}" class="mr-2">
                    <div class="flex">
                        <input type="text" name="search" class="form-control px-4 py-2 border rounded-l-md" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        <button class="bg-green-600 text-white px-4 py-2 border rounded-r-md hover:bg-green-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded">
                <thead>
                <tr>
                    <th class="px-6 py-3 border-b">ID</th>
                    <th class="px-6 py-3 border-b">Name</th>
                    <th class="px-6 py-3 border-b">Email</th>
                    <th class="px-6 py-3 border-b">Create Date</th>
                    <th class="px-6 py-3 border-b">Status</th>
                    <th class="px-6 py-3 border-b">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $user->id }}</td>
                        <td class="px-6 py-4 border-b">{{ $user->name }}</td>
                        <td class="px-6 py-4 border-b">{{ $user->email }}</td>
                        <td class="px-6 py-4 border-b">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 border-b">
                            <span class="px-2 py-1 rounded-full text-sm {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->status ? 'Active' : 'Locked' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 border-b flex gap-2">
                            <form method="POST" action="{{ route('admin.accounts.toggle', $user) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full {{ $user->status ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded">
                                    {{ $user->status ? 'Deactivate' : 'Active' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.accounts.destroy', $user) }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure you want to delete?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-6 py-4 border-b">No accounts found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
