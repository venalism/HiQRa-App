<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Absensi QR')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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