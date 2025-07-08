<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Absensi QR')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Header -->
    <main class="flex-grow">
        <header class="bg-white shadow-sm">
            <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900">
                    @yield('header')
                </h1>
                <button onclick="window.history.back()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg inline-flex items-center btn-hover">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
            </div>
        </header>

        {{-- Konten Dinamis --}}
        <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p class="text-sm">&copy; {{ date('Y') }} Aplikasi Himatif</p>
    </footer>
    @stack('scripts')
</body>

</html>