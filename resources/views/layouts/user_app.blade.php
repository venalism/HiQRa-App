<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Absensi QR')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .red-gradient {
            background: linear-gradient(135deg, #dc2626, #991b1b);
        }

        .text-red-gradient {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }


        #sidebar,
        #mainContent,
        #topbar {
            transition: all 0.3s ease-in-out;
        }

        #sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar-open #sidebar {
            transform: translateX(0);
        }

        @media (min-width: 768px) {

            .sidebar-open #mainContent,
            .sidebar-open #topbar {
                margin-left: 16rem;
            }
        }

        @media (max-width: 767px) {
            .sidebar-open #sidebar-overlay {
                opacity: 1;
                pointer-events: auto;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <h1 class="text-base px-8 md:text-xl font-bold text-gray-900">@yield('header')</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="window.history.back()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-2 text-sm rounded-lg md:py-2 md:px-4 md:text-base">
                            ← Kembali
                        </button>
                        <form method="POST" action="{{ route('user.logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-2 text-sm rounded-lg md:py-2 md:px-4 md:text-base">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>