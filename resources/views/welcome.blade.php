<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HiQRa - Aplikasi Absensi Himatif</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Footer Styles */
        .footer-section {
            background: #0d0d0d;
            border-top: 1px solid #333;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-main {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 3rem;
            align-items: start;
        }

        .footer-brand .footer-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 0.5rem;
        }

        .footer-tagline {
            color: #dc2626;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .footer-description {
            color: #d1d5db;
            line-height: 1.6;
            max-width: 300px;
        }

        .footer-links {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .footer-column .footer-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .footer-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-menu li {
            margin-bottom: 0.5rem;
        }

        .footer-link {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #dc2626;
        }

        .footer-social {
            text-align: center;
        }

        .footer-social .footer-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid #dc2626;
            border-radius: 50%;
            color: #dc2626;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-link:hover {
            background: #dc2626;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 2rem;
            border-top: 1px solid #333;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copyright p,
        .footer-info p {
            color: #9ca3af;
            margin: 0;
        }

        .footer-info p {
            color: #dc2626;
        }

        @media (max-width: 768px) {
            .footer-main {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-links {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .social-links {
                flex-wrap: wrap;
            }
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            color: #ffffff;
            overflow-x: hidden;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding-top: 90px;
        }

        .hero-content {
            text-align: center;
            max-width: 1200px;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: #ffffff;
            margin-bottom: 2rem;
            font-weight: 500;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #d1d5db;
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
        }

        .btn-secondary {
            background: transparent;
            color: #ffffff;
            padding: 1rem 2rem;
            border: 2px solid #ffffff;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #ffffff;
            color: #1a1a1a;
            transform: translateY(-2px);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #d1d5db;
            line-height: 1.6;
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

        .qr-animation {
            position: absolute;
            top: 50%;
            right: 10%;
            transform: translateY(-50%);
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(-50%) rotate(0deg);
            }

            50% {
                transform: translateY(-60%) rotate(5deg);
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding-top: 100px;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                max-width: 300px;
            }

            .qr-animation {
                display: none;
            }

            .nav-links {
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="nav-header">
        <div class="nav-container">
            <a href="/" class="logo">HiQRa</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">
                            Register
                        </a>
                    @endif
                @endauth

            @endif

        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">HiQRa</h1>
            <h2 class="hero-subtitle">Himatif QR Attendance</h2>
            <p class="hero-description">
                Sistem absensi digital modern untuk Himpunan Mahasiswa Teknik Informatika.
                Kelola kehadiran dengan mudah melalui teknologi QR Code yang praktis dan efisien.
            </p>

            <div class="cta-buttons">
                <a href="/dashboard" class="btn-primary">Mulai Absensi</a>
                <a href="{{ route('guide') }}" class="btn-secondary">Pelajari Lebih Lanjut</a>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <rect x="7" y="7" width="3" height="3" />
                            <rect x="14" y="7" width="3" height="3" />
                            <rect x="7" y="14" width="3" height="3" />
                            <rect x="11" y="11" width="2" height="2" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Scan QR Code</h3>
                    <p class="feature-description">
                        Absensi kegiatan Himatif dengan mudah hanya dengan scan QR code.
                        Proses cepat dan akurat untuk semua anggota.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2z" />
                            <path d="M21 11h-4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2z" />
                            <path d="M7 2v9" />
                            <path d="M17 2v9" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Laporan Real-time</h3>
                    <p class="feature-description">
                        Dapatkan laporan kehadiran secara real-time dengan dashboard
                        yang informatif dan mudah dipahami.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M4 4h16v4H4z"></path>
                            <path d="M4 10h16v4H4z"></path>
                            <path d="M4 16h16v4H4z"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">Kelola Data Peserta</h3>
                    <p class="feature-description">
                        Tambah, ubah, dan hapus data peserta dengan cepat dan terstruktur untuk
                        mendukung keakuratan absensi.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Mudah Digunakan</h3>
                    <p class="feature-description">
                        Interface yang intuitif dan user-friendly memudahkan
                        semua anggota untuk menggunakan sistem absensi.
                    </p>
                </div>

            </div>
        </div>

        <!-- Floating QR Animation -->
        <div class="qr-animation">
            <svg width="300" height="300" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20" y="20" width="160" height="160" rx="8" fill="none" stroke="#dc2626" stroke-width="2" />
                <rect x="30" y="30" width="30" height="30" rx="4" fill="#dc2626" />
                <rect x="140" y="30" width="30" height="30" rx="4" fill="#dc2626" />
                <rect x="30" y="140" width="30" height="30" rx="4" fill="#dc2626" />
                <rect x="35" y="35" width="20" height="20" rx="2" fill="white" />
                <rect x="145" y="35" width="20" height="20" rx="2" fill="white" />
                <rect x="35" y="145" width="20" height="20" rx="2" fill="white" />
                <rect x="85" y="85" width="30" height="30" rx="4" fill="#dc2626" />
                <rect x="90" y="90" width="20" height="20" rx="2" fill="white" />
            </svg>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-main">
                    <div class="footer-brand">
                        <h3 class="footer-logo">HiQRa</h3>
                        <p class="footer-tagline">Himatif QR Attendance</p>
                        <p class="footer-description">
                            Sistem absensi digital modern untuk Himpunan Mahasiswa Teknik Informatika.
                            Memudahkan pengelolaan kehadiran dengan teknologi QR Code.
                        </p>
                    </div>

                    <div class="footer-links">
                        <div class="footer-column">
                            <h4 class="footer-title">Aplikasi</h4>
                            <ul class="footer-menu">
                                <li><a href="/dashboard" class="footer-link">Dashboard</a></li>
                                <li><a href="/scan" class="footer-link">Scan QR</a></li>
                                <li><a href="/events" class="footer-link">Event</a></li>
                                <li><a href="/reports" class="footer-link">Laporan</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h4 class="footer-title">Himatif</h4>
                            <ul class="footer-menu">
                                <li><a href="/about" class="footer-link">Tentang Kami</a></li>
                                <li><a href="/kegiatan" class="footer-link">Kegiatan</a></li>
                                <li><a href="/pengurus" class="footer-link">Pengurus</a></li>
                                <li><a href="/contact" class="footer-link">Kontak</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h4 class="footer-title">Dukungan</h4>
                            <ul class="footer-menu">
                                <li><a href="/help" class="footer-link">Bantuan</a></li>
                                <li><a href="/faq" class="footer-link">FAQ</a></li>
                                <li><a href="/privacy" class="footer-link">Privacy Policy</a></li>
                                <li><a href="/terms" class="footer-link">Terms of Service</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-social">
                    <h4 class="footer-title">Ikuti Himatif</h4>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.017 0z" />
                            </svg>
                        </a>
                        <a href="#" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>&copy; 2024 HiQRa - Himpunan Mahasiswa Teknik Informatika. All rights reserved.</p>
                </div>
                <div class="footer-info">
                    <p>Dikembangkan dengan ❤️ untuk Himatif</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>