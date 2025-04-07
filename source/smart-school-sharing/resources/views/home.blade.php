@extends('layouts.app')

@section('content')
    <!-- Header -->

    <div class="flex max-w-screen-lg mx-auto">
        <!-- Left Ad Section -->
        <div class="ads">Advertisement</div>

        <div class="flex-1">
            <!-- Search Section -->
            <section class="p-8 text-center">
                <h2 class="text-3xl font-bold text-green-700">Give & Get Smart – Share Your Unused School Supplies!</h2>
                <div class="mt-4">
                    <input type="text" placeholder="Search for supplies..." class="p-2 text-black rounded-md">
                    <button class="bg-green-600 text-white p-2 rounded-md ml-2">Search</button>
                </div>
            </section>

            <!-- Slider Section -->
            <section class="p-8 bg-yellow-100">
                <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Featured Items</h2>
                <div class="slider">
                    <div class="slider-item">📘 Item 1</div>
                    <div class="slider-item">🖋 Item 2</div>
                    <div class="slider-item">🖥 Item 3</div>
                    <div class="slider-item">📖 Item 4</div>
                </div>
            </section>

            <!-- Categories Section -->
            <section class="p-8">
                <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Popular Categories</h2>
                <div class="category-grid">
                    <div class="category-card">📚 Books</div>
                    <div class="category-card">✏️ Stationery</div>
                    <div class="category-card">💻 Gadgets</div>
                    <div class="category-card">🎒 Backpacks</div>
                    <div class="category-card">🎨 Art Supplies</div>
                    <div class="category-card">📝 Notebooks</div>
                </div>
            </section>
        </div>

        <!-- Right Ad Section -->
        <div class="ads">Advertisement</div>
    </div>
@endsection
