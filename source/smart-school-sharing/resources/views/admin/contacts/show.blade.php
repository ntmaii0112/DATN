@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-8 bg-white shadow rounded-md">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-green-700">Contact Message Details</h2>
            <a href="{{ route('admin.contacts.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column: Contact Info -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Sender Name</p>
                    <p class="mt-1 text-gray-900">{{ $contact->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="mt-1 text-blue-700 underline break-words">{{ $contact->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Received At</p>
                    <p class="mt-1 text-gray-900">{{ $contact->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <!-- Right Column: Message Content -->
            <div>
                <p class="text-sm text-gray-500 mb-2">Message</p>
                <div class="p-4 border rounded bg-gray-50 text-gray-800 whitespace-pre-line break-words">
                    {{ $contact->message }}
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end gap-3">
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this contact message?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
