@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <section class="text-center mb-16">
            <h1 class="text-4xl font-bold text-green-700 mb-6">About Smart School Sharing</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We're on a mission to reduce waste and make education more accessible by connecting students who need school supplies with those who have extras.
            </p>
        </section>

        <!-- Mission Section -->
        <section class="mb-16">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-green-700 mb-4">Our Mission</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-700 mb-4">
                            Every year, tons of usable school supplies end up in landfills while many students struggle to afford basic materials. We bridge this gap through a sharing economy platform.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-green-600 mr-2">✓</span>
                                <span>Reduce educational waste by 80%</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-600 mr-2">✓</span>
                                <span>Help low-income students access free supplies</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex justify-center">
                        <img src="{{ asset('images/about-mission.jpg') }}" alt="Students sharing books" class="rounded-lg h-64 object-cover shadow">
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-green-700 mb-8 text-center">Meet The Team</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Team Member 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gray-200 overflow-hidden">
                        <img src="{{ asset('images/team-member1.jpg') }}" alt="Team Member" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-lg">Nguyen Thanh Mai</h3>
                    <p class="text-gray-600">Founder & CEO</p>
                </div>
                <!-- Add more team members as needed -->
            </div>
        </section>

        <!-- Stats Section -->
        <section class="bg-green-700 text-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-8 text-center">Our Impact</h2>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div>
                    <p class="text-4xl font-bold">1,200+</p>
                    <p class="text-gray-200">Items Shared</p>
                </div>
                <div>
                    <p class="text-4xl font-bold">500+</p>
                    <p class="text-gray-200">Students Helped</p>
                </div>
                <div>
                    <p class="text-4xl font-bold">10+</p>
                    <p class="text-gray-200">Schools Participating</p>
                </div>
            </div>
        </section>
    </div>
@endsection
