<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Absensi QR')</title>
    <script src="{{ asset('css/css_tailwind.css') }}" rel="stylesheet"></script>
    <style>
        /* Simple animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
        
        .btn-hover {
            transition: all 0.2s ease;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .card-simple {
            transition: box-shadow 0.2s ease;
        }
        
        .card-simple:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom red gradient */
        .red-gradient {
            background: linear-gradient(135deg, #dc2626, #991b1b);
        }
        
        .text-red-gradient {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
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