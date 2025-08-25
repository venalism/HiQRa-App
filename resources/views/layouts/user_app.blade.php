<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Absensi QR')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="#">
                                <h1 class="text-lg font-bold">HiQRa Dashboard</h1>
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center ms-6">
                        <div class="font-medium text-base text-gray-800 mr-4">{{ Auth::user()->nama }}</div>
                        <form method="POST" action="{{ route('user.logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>