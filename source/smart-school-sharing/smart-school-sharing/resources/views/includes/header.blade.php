<header class="bg-green-600 text-white shadow-md p-4 flex justify-between items-center w-full flex-wrap">
    <h1 class="text-xl font-bold text-center flex-1">Smart School Sharing</h1>
    <nav class="flex-1">
        <ul class="flex flex-wrap justify-center space-x-4">
            <li><a href="{{ url('/') }}" class="hover:text-yellow-300">Home</a></li>
            <li><a href="#" class="hover:text-yellow-300">Categories</a></li>
            <li><a href="#" class="hover:text-yellow-300">How It Works</a></li>
            <li><a href="#" class="hover:text-yellow-300">About</a></li>
            <li><a href="#" class="hover:text-yellow-300">Contact</a></li>
            <li><a href="#" class="hover:text-yellow-300">🛒</a></li>
            @guest
                <li><a href="{{ route('login') }}" class="hover:text-yellow-300">👤 Login</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-yellow-300">📝 Register</a></li>
            @else
                <li><a href="#" class="hover:text-yellow-300">{{ Auth::user()->name }}</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-yellow-300">🚪 Logout</button>
                    </form>
                </li>
            @endguest
        </ul>
    </nav>
</header>
