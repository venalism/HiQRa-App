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

        .guide-content h2 {
            font-size: 1.75rem;
            color: #ffffff;
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 0.5rem;
        }

        .guide-content h3 {
            font-size: 1.25rem;
            color: #e5e7eb;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .guide-content p,
        .guide-content li {
            color: #d1d5db;
            line-height: 1.7;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .guide-content ul {
            padding-left: 1.5rem;
            list-style-type: '✓  ';
        }

        .guide-content .step {
            background-color: rgba(45, 45, 45, 0.85);
            padding: 1.5rem;
            border-radius: 0.75rem;
            border-left: 4px solid #dc2626;
            margin-bottom: 1.5rem;
        }

        .guide-content .important {
            background-color: rgba(220, 38, 38, 0.1);
            border-left-color: #fca5a5;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }

        .guide-content .important p {
            margin: 0;
            color: #fca5a5;
        }

        /* Style untuk gambar */
        .step-image {
            width: 100%;
            max-width: 600px;
            height: 300px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px dashed rgba(220, 38, 38, 0.5);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.5rem 0;
            color: #9ca3af;
            font-style: italic;
            text-align: center;
            padding: 2rem;
        }

        .step-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .image-caption {
            font-size: 0.875rem;
            color: #9ca3af;
            text-align: center;
            margin-top: 0.5rem;
            font-style: italic;
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
            <h1 class="guide-title">Panduan Alur Kerja Admin</h1>
            <div class="guide-content">
                <p>Selamat datang di HiQRa! Halaman ini akan memandu Anda (sebagai Admin) untuk menggunakan aplikasi
                    dari awal hingga siap digunakan untuk absensi.</p>

                <h2>Langkah 1: Login & Memahami Dashboard</h2>
                <div class="step">
                    <p>Pertama, login ke akun admin Anda. Anda akan disambut oleh <b>Dashboard</b>, yang menampilkan
                        ringkasan data penting seperti jumlah kegiatan, peserta, dan panitia.</p>
                    
                    <div class="step-image">
                        <img src="{{ asset('images/guide1.png') }}" alt="Guide 1">
                    </div>
                    <p class="image-caption">Contoh tampilan Dashboard admin HiQRa</p>
                </div>

                <h2>Langkah 2: Mengisi Master Data (WAJIB)</h2>
                <div class="step">
                    <p>Ini adalah <b>langkah paling penting</b> sebelum Anda bisa menambahkan pengguna. Master data
                        adalah
                        kumpulan data dasar yang akan digunakan di seluruh aplikasi. Buka sidebar navigasi dan isi data
                        berikut secara berurutan:</p>

                    <div class="step-image">
                         <img src="{{ asset('images/guide2.png') }}" alt="Guide 2">
                    =
                    </div>
                    <p class="image-caption">Sidebar navigasi untuk mengakses menu master data</p>

                    <h3>Untuk Peserta:</h3>
                    <ul>
                        <li><strong>Manajemen Prodi:</strong> Isi semua program studi yang ada (contoh: Teknik
                            Informatika, DKV).</li>
                        <li><strong>Manajemen Kelas:</strong> Isi semua kelas yang relevan (contoh: IF-45-01,
                            DKV-45-INT).</li>
                    </ul>

                    <div class="step-image">
                        <img src="{{ asset('images/guide3.png') }}" alt="Guide 3">
                    </div>
                    <p class="image-caption">Halaman manajemen Program Studi dan Kelas</p>

                    <h3>Untuk Panitia:</h3>
                    <ul>
                        <li><strong>Manajemen Jabatan:</strong> Isi semua jabatan kepanitiaan (contoh: Ketua Pelaksana,
                            Sekretaris, Bendahara).</li>
                        <li><strong>Manajemen Divisi:</strong> Isi semua divisi yang ada (contoh: Divisi Acara, Divisi
                            Keamanan).</li>
                    </ul>

                    <div class="step-image">
                        <img src="{{ asset('images/guide4.png') }}" alt="Guide 4">
                    </div>
                    <p class="image-caption">Halaman manajemen Jabatan dan Divisi panitia</p>

                    <div class="important">
                        <p><strong>PENTING:</strong> Anda tidak bisa menambahkan Peserta atau Panitia jika data-data di
                            atas masih kosong.</p>
                    </div>
                </div>

                <h2>Langkah 3: Menambahkan Pengguna</h2>
                <div class="step">
                    <p>Setelah master data lengkap, sekarang Anda bisa mendaftarkan pengguna:</p>
                    <ul>
                        <li><strong>Manajemen Panitia:</strong> Tambahkan data panitia. Anda akan diminta memilih
                            Jabatan dan Divisi dari data yang sudah Anda isi di Langkah 2.</li>
                        <li><strong>Manajemen Peserta:</strong> Tambahkan data peserta. Anda akan diminta memilih Prodi
                            dan Kelas.</li>
                    </ul>

                    <div class="step-image">
                        <img src="{{ asset('images/guide5.png') }}" alt="Guide 5">
                    </div>
                    <p class="image-caption">Form untuk menambahkan data panitia baru</p>

                    <div class="step-image">
                        <img src="{{ asset('images/guide6.png') }}" alt="Guide 6">
                
                    </div>
                    <p class="image-caption">Form untuk menambahkan data peserta baru</p>

                    <p>Setiap panitia dan peserta yang berhasil dibuat akan otomatis memiliki QR Code unik mereka
                        sendiri.</p>

                    <div class="step-image">
                        <img src="{{ asset('images/guide7.png') }}" alt="Guide 7">
        
                    </div>
                    <p class="image-caption">Contoh QR Code yang digenerate untuk setiap pengguna</p>
                </div>

                <h2>Langkah 4: Membuat Kegiatan</h2>
                <div class="step">
                    <p>Sekarang, buat acara atau kegiatan yang akan diadakan.</p>
                    <ul>
                        <li>Buka menu <b>Manajemen Kegiatan</b> dan klik "Tambah Kegiatan".</li>
                        <li>Isi nama kegiatan, tanggal, dan deskripsi.</li>
                    </ul>

                    <div class="step-image">
                        <img src="{{ asset('images/guide8.png') }}" alt="Guide 8">
                    </div>
                    <p class="image-caption">Halaman manajemen kegiatan</p>

                    <div class="step-image">
                        {{-- perlu diganti nanti --}}
                        <img src="{{ asset('images/guide9.png') }}" alt="Guide 9">
                    </div>
                    <p class="image-caption">Form untuk menambahkan kegiatan baru</p>

                    <p>Kegiatan yang Anda buat di sini akan menjadi pilihan saat melakukan absensi.</p>
                </div>

                <h2>Langkah 5: Melakukan Scan QR (Absensi)</h2>
                <div class="step">
                    <p>Ini adalah fitur utama yang akan digunakan saat acara berlangsung.</p>
                    <ul>
                        <li>Buka menu <b>Scan QR</b> dari sidebar.</li>
                        <li>Pilih kegiatan mana yang sedang berlangsung.</li>
                        <li>Arahkan kamera ke QR Code milik panitia atau peserta untuk mencatat kehadiran mereka.</li>
                    </ul>

                    <div class="step-image">
                        <!-- <img src="path/to/scan-qr-interface.png" alt="Screenshot Interface Scan QR"> -->
                        Gambar Interface Scan QR - Halaman untuk memilih kegiatan dan melakukan scanning QR Code
                    </div>
                    <p class="image-caption">Interface untuk scanning QR Code</p>

                    <div class="step-image">
                        <!-- <img src="path/to/camera-scanning.png" alt="Screenshot Kamera Scanning QR"> -->
                        Gambar Kamera Scanning - Tampilan kamera saat melakukan scan QR Code
                    </div>
                    <p class="image-caption">Tampilan kamera saat melakukan scan QR Code</p>

                    <div class="step-image">
                        <!-- <img src="path/to/attendance-success.png" alt="Screenshot Absensi Berhasil"> -->
                        Gambar Konfirmasi Absensi - Notifikasi ketika absensi berhasil tercatat
                    </div>
                    <p class="image-caption">Konfirmasi ketika absensi berhasil tercatat</p>

                    <div class="important">
                        <p><strong>PENTING:</strong> Fitur Scan QR hanya bisa berfungsi jika minimal ada satu pengguna
                            (Peserta/Panitia) dan satu Kegiatan yang sudah terdaftar di sistem.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>

</html>