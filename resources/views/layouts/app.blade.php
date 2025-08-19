<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>

<body class="bg-gray-100 text-gray-800 h-screen overflow-hidden">

    <!-- Tombol Toggle Sidebar -->
    <button onclick="toggleSidebar()"
        class="fixed top-4 left-4 z-50 bg-gray-700 text-white px-3 py-2 rounded hover:bg-gray-600">
        ☰
    </button>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white p-4 transition-transform duration-300 transform -translate-x-64 z-40">
        <div class="text-2xl font-bold mb-8 text-center text-red-gradient">HiQRa</div>
        <nav>
            <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Dashboard</a>
            <h3 class="px-4 mt-4 mb-2 text-xs text-gray-400 uppercase">Manajemen</h3>
            <a href="{{ route('kegiatan.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Kegiatan</a>
            <a href="{{ route('panitia.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Panitia</a>
            <a href="{{ route('peserta.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Peserta</a>
            <h3 class="px-4 mt-4 mb-2 text-xs text-gray-400 uppercase">Riwayat</h3>
            <a href="{{ route('riwayat.peserta') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Absensi Peserta</a>
            <a href="{{ route('riwayat.panitia') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Absensi Panitia</a>
            <h3 class="px-4 mt-4 mb-2 text-xs text-gray-400 uppercase">Master Data</h3>
            <a href="{{ route('master.akademik') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Prodi & Kelas</a>
            <a href="{{ route('master.organisasi') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Jabatan & Divisi</a>
            <a href="{{ route('import.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Import Data</a>
        </nav>
    </aside>

    <!-- Konten Utama -->
    <div id="mainContent" class="ml-0 transition-all duration-300 flex flex-col h-screen">

        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-30">
            <div class="py-4 px-6 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900">@yield('header')</h1>
                <div class="flex items-center gap-2">
                    <button onclick="window.history.back()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">
                        ← Kembali
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="mt-20 p-6 overflow-y-auto flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white text-center py-4">
            <p class="text-sm">&copy; {{ date('Y') }} Aplikasi Himatif</p>
        </footer>
    </div>

    <!-- Script Toggle Sidebar -->
    <script>
        let sidebarVisible = false;
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            sidebarVisible = !sidebarVisible;

            if (sidebarVisible) {
                sidebar.classList.remove('-translate-x-64');
                sidebar.classList.add('translate-x-0');
                mainContent.classList.add('ml-64');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-64');
                mainContent.classList.remove('ml-64');
            }
        }
    </script>

    @stack('scripts')
</body>

</html>