@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Items in "{{ $category->name }}"</h2>

        @if($items->isEmpty())
            <p>No items found in this category.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($items as $item)
                    <a href="{{ url('/items/' . $item->id) }}" class="block border p-4 rounded shadow hover:shadow-lg transition">
                        <h3 class="font-semibold text-lg">{{ $item->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $item->description }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
