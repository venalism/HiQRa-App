<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<body class="bg-gray-100 text-gray-800">

    <div class="flex">
        @if(Auth::check() && Auth::user()->role === 'admin')
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
                <div class="text-2xl font-bold mb-8 text-center text-red-gradient">HiQRa</div>
                <nav>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
                    <h3 class="px-4 mt-4 mb-2 text-xs text-gray-400 uppercase tracking-wider">Manajemen</h3>
                    <a href="{{ route('kegiatan.index') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Kegiatan</a>
                    <a href="{{ route('panitia.index') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Panitia</a>
                    <a href="{{ route('peserta.index') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Peserta</a>

                    <h3 class="px-4 mt-4 mb-2 text-xs text-gray-400 uppercase tracking-wider">Riwayat</h3>
                    <a href="{{ route('riwayat.peserta') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Absensi
                        Peserta</a>
                    <a href="{{ route('riwayat.panitia') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Absensi
                        Panitia</a>

                    <h3 class="px-4 mt-4 mb-2 text-xs text-gray-400 uppercase tracking-wider">Master Data</h3>
                    <a href="{{ route('master.akademik') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Prodi &
                        Kelas</a>
                    <a href="{{ route('master.organisasi') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Jabatan &
                        Divisi</a>
                    <a href="{{ route('import.index') }}"
                        class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                        Import Data
                    </a>
                </nav>
            </aside>
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-900">
                        @yield('header')
                    </h1>

                    <div class="flex items-center gap-2">
                        <!-- Tombol Kembali -->
                        <button onclick="window.history.back()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg inline-flex items-center btn-hover">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </button>

                        <!-- Tombol Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg inline-flex items-center btn-hover">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V3"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Konten Dinamis --}}
            <main class="flex-grow p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p class="text-sm">&copy; {{ date('Y') }} Aplikasi Himatif</p>
    </footer>

    @stack('scripts')
</body>

</html>