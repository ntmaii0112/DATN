<header class="bg-white text-blue-900 shadow-md py-6 border-b">
    <div class="container mx-auto px-4 flex justify-between items-center flex-wrap">
        <!-- LOGO -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span class="text-2xl font-bold text-blue-900">Smart School Sharing</span>
        </a>

        <!-- NAVIGATION -->
        <nav class="mt-4 md:mt-0 w-full md:w-auto">
            <ul class="flex flex-wrap justify-center md:justify-end space-x-6 text-lg font-semibold items-center">
                <li><a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-blue-600 transition">About</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-blue-600 transition">Contact</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="hover:text-blue-600">Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-blue-600">Register</a></li>
                @else
                    <li>
                        <a href="{{ url('/items/create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Đăng tin
                        </a>
                    </li>

                    <!-- Dropdown with Alpine.js -->
                    <li x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button
                            @click="open = !open"
                            @mouseover="open = true"
                            class="hover:text-blue-600 focus:outline-none flex items-center space-x-1"
                        >
                            <span>{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                                 :class="{ 'transform rotate-180': open }"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <ul
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50"
                        >
                            <li>
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <span class="mr-2">📦</span> My Transactions
                                </a>
                            </li>
                            <li class="border-t border-gray-100"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <span class="mr-2">🚪</span> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </nav>
    </div>
</header>
