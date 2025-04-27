@extends('layouts.app')

@section('content')
    <h2>Kết quả tìm kiếm cho: "{{ $query }}"</h2>

    @if($items->isEmpty())
        <p>Không tìm thấy kết quả.</p>
    @else
        <div class="grid">
            @foreach($items as $item)
                <div class="card">
                    <h3>{{ $item->name }}</h3>
                    <p>{{ $item->description }}</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection
