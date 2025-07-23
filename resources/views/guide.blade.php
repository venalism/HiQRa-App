<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Penggunaan - HiQRa</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        /* CSS dari welcome.blade.php */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            color: #ffffff;
            overflow-x: hidden;
        }

        .nav-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #dc2626;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #dc2626;
        }

        .guide-section {
            padding-top: 120px;
            padding-bottom: 4rem;
        }

        .guide-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .guide-title {
            font-size: 3rem;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 2rem;
            text-align: center;
        }

        .guide-content h3 {
            font-size: 1.5rem;
            color: #ffffff;
            margin-top: 2rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 0.5rem;
        }

        .guide-content p,
        .guide-content li {
            color: #d1d5db;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .guide-content ul {
            padding-left: 1.5rem;
        }

        .guide-content code {
            background-color: #333;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-family: 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', "Liberation Mono", "Courier New", 'monospace';
        }
    </style>
</head>

<body>
    <nav class="nav-header">
        <div class="nav-container">
            <a href="/" class="logo">HiQRa</a>
            <div class="nav-links">
                <a href="/" class="nav-link">Home</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="guide-section">
        <div class="guide-container">
            <h1 class="guide-title">Panduan Penggunaan HiQRa</h1>
            <div class="guide-content">
                <h3>Untuk Admin</h3>
                <p>
                    Sebagai admin, Anda memiliki akses penuh untuk mengelola seluruh aspek aplikasi HiQRa. Berikut adalah panduan singkat untuk setiap fitur:
                </p>
                <ul>
                    <li><strong>Dashboard:</strong> Halaman utama setelah login, menampilkan ringkasan data seperti jumlah peserta, panitia, kegiatan, dan rekap absensi harian.</li>
                    <li><strong>Manajemen Kegiatan:</strong> Buat, edit, dan hapus kegiatan. Setiap kegiatan akan memiliki QR Code unik yang bisa di-generate.</li>
                    <li><strong>Manajemen Peserta & Panitia:</strong> Kelola data peserta dan panitia, termasuk membuat akun baru, mengubah data, dan menghapus akun.</li>
                    <li><strong>Scan QR Code:</strong> Fitur untuk melakukan absensi dengan memindai QR Code milik peserta atau panitia.</li>
                    <li><strong>Rekap Absensi:</strong> Lihat riwayat dan rekapitulasi kehadiran dari setiap kegiatan, dengan opsi untuk mengekspor data ke dalam format Excel atau PDF.</li>
                </ul>

                <h3>Untuk Panitia</h3>
                <p>
                    Panitia dapat membantu admin dalam mengelola kegiatan dan absensi. Berikut adalah hal yang bisa Anda lakukan:
                </p>
                <ul>
                    <li><strong>Scan QR Code:</strong> Sama seperti admin, panitia dapat melakukan scan QR Code untuk mencatat kehadiran.</li>
                    <li><strong>Melihat Dashboard Pribadi:</strong> Panitia dapat melihat rekap kehadiran mereka sendiri untuk kegiatan yang diikutinya.</li>
                </ul>

                <h3>Untuk Peserta</h3>
                <p>
                    Peserta adalah pengguna utama yang akan melakukan absensi. Berikut adalah alurnya:
                </p>
                <ul>
                    <li><strong>Mendapatkan QR Code:</strong> Setiap peserta akan memiliki QR Code unik yang dapat diakses melalui profil mereka (jika fitur login peserta diaktifkan) atau dibagikan oleh admin/panitia.</li>
                    <li><strong>Proses Absensi:</strong> Tunjukkan QR Code Anda kepada panitia yang bertugas pada saat kegiatan berlangsung untuk dipindai.</li>
                    <li><strong>Cek Riwayat Kehadiran:</strong> Peserta dapat melihat riwayat kehadiran mereka sendiri melalui halaman profil (jika tersedia).</li>
                </ul>
            </div>
        </div>
    </section>
</body>

</html>