<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Absensi QR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            border-top: 4px solid #dc2626;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #1f2937;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header .subtitle {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .status-message {
            background-color: #d4f4dd;
            color: #1f7a2e;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #4ade80;
            font-size: 0.9rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f9fafb;
        }

        input:focus {
            outline: none;
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        input:invalid {
            border-color: #ef4444;
        }

        .login-button {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .error-message {
            background-color: #fde8e8;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-top: 1rem;
            border-left: 4px solid #dc2626;
            font-size: 0.9rem;
        }

        .footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.85rem;
        }

        .login-back-button {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.85rem;
            margin-top: 0.5rem;
            background: #fff;
            color: #dc2626;
            border: 2px solid #dc2626;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.06);
        }

        .login-back-button:hover,
        .login-back-button:focus {
            background: #dc2626;
            color: #fff;
            border-color: #991b1b;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.18);
        }

        /* Loading state */
        .loading {
            position: relative;
            color: transparent;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
                margin: 10px;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🔐 Login Panitia</h1>
            <p class="subtitle">Masuk untuk mengelola absensi kegiatan</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="input-group">
                <label for="email">📧 Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="masukkan email anda">
            </div>

            <div class="input-group">
                <label for="password">🔒 Password</label>
                <input id="password" type="password" name="password" required placeholder="masukkan password anda">
            </div>

            <button type="submit" class="login-button" id="loginBtn">
                Masuk
            </button>
            <a href="/" class="login-back-button">
                ← Kembali ke Beranda
            </a>
            @error('email')
                <div class="error-message">
                    {{ $message }}
                </div>
            @enderror
        </form>

        <div class="footer">
            <p>&copy; 2024 Aplikasi Absensi QR</p>
        </div>
    </div>

    <script>
        // Add loading state to button
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // Add enter key support
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').submit();
            }
        });
    </script>
</body>

</html>
