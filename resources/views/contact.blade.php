<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kontak - HiQRa</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
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

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-section {
            min-height: 100vh;
            padding: 140px 2rem 4rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 1.5rem;
        }

        .page-content p {
            color: #d1d5db;
            line-height: 1.8;
            margin-bottom: 1.2rem;
            font-size: 1.05rem;
        }

        .page-content a {
            color: #dc2626;
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

            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <nav class="nav-header">
        <div class="nav-container">
            <a href="/" class="logo">HiQRa</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-link">
                    Dashboard
                </a>
            @else
                <div class="nav-auth">
                    <a href="{{ route('admin.login') }}" class="nav-link">Admin</a>
                    <a href="{{ route('panitia.login') }}" class="nav-link">Panitia</a>
                    <a href="{{ route('peserta.login') }}" class="nav-link">Peserta</a>
                </div>
            @endauth
        </div>
    </nav>

    <section class="page-section">
        <h1 class="page-title">Kontak</h1>
        <div class="page-content">
            <p>Punya pertanyaan seputar HiQRa atau ingin menghubungi Himatif? Silakan hubungi kami melalui:</p>
            <p>📧 Email: himatif@kampus.ac.id</p>
            <p>📱 Instagram: <a href="https://www.instagram.com/himatif.ulbi">@himatif.ulbi</a></p>
            <p>📍 Sekretariat: Gedung Teknik Informatika</p>
        </div>
    </section>

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
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h4 class="footer-title">Himatif</h4>
                            <ul class="footer-menu">
                                <li><a href="/about" class="footer-link">Tentang Kami</a></li>
                                <li><a href="/kegiatan" class="footer-link">Kegiatan</a></li>
                                <li><a href="/contact" class="footer-link">Kontak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="footer-social">
                    <h4 class="footer-title">Ikuti Himatif</h4>
                    <div class="social-links">
                        <a href="https://www.tiktok.com/@himatif.ulbi?lang=id-ID" target="_blank" rel="noopener" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/Himatif.Poltekpos" target="_blank" rel="noopener" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/himatif.ulbi?utm_source=ig_web_button_share_sheet&stkn=ZDNlZDc0MzIxNw==" class="social-link">
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