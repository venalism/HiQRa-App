<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Absensi QR')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b-2 border-red-600">
        <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-bold text-gray-800 text-center">
                @yield('header')
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-auto">
        <p class="text-sm">&copy; 2024 Aplikasi Himatif</p>
    </footer>
</body>

</html>