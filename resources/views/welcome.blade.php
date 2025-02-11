<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel - Welcome</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <div class="min-h-screen flex flex-col items-center justify-center">
            <header class="w-full max-w-6xl px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Laravel App</h1>
                @if (Route::has('login'))
                    <nav class="flex space-x-4">
                        @auth
                            <a href="{{ url('/welcome') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 border border-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Register</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>
            
            <main class="w-full max-w-4xl text-center py-16">
                <h2 class="text-4xl font-extrabold mb-4">Welcome to My Laravel App</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">Build powerful applications with Laravel and elevate your development experience.</p>
                <a href="{{ route('register') }}" class="px-6 py-3 bg-red-500 text-white rounded-md text-lg font-semibold hover:bg-red-600 transition">Get Started</a>
            </main>
            
            <footer class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </body>
</html>
