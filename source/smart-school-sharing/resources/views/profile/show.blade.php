@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h1>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Name</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->phone ?? 'Not provided' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Address</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->address ?? 'Not provided' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Created</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->created_at->format('F j, Y') }}</p>
                </div>

                <div class="pt-4">
                    <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
