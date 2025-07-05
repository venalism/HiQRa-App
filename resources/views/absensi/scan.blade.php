<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code Absensi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #dc2626;
        }

        .header h1 {
            color: #1f2937;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header .kegiatan {
            color: #dc2626;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .scanner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        #reader {
            width: 100%;
            max-width: 400px;
            border: 3px solid #dc2626;
            border-radius: 12px;
            background: white;
            box-shadow: 0 2px 10px rgba(220, 38, 38, 0.1);
        }

        #result {
            margin-top: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success {
            background-color: #d4f4dd;
            color: #1f7a2e;
            border: 2px solid #4ade80;
        }

        .error {
            background-color: #fde8e8;
            color: #991b1b;
            border: 2px solid #dc2626;
        }

        .loading {
            background-color: #f3f4f6;
            color: #374151;
            border: 2px solid #9ca3af;
        }

        .instructions {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
        }

        .instructions h3 {
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .instructions p {
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-success {
            background-color: #4ade80;
            animation: pulse 2s infinite;
        }

        .status-error {
            background-color: #dc2626;
        }

        .status-loading {
            background-color: #9ca3af;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #1f2937;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-button:hover {
            background: #374151;
            transform: scale(1.05);
        }

        @media (max-width: 640px) {
            .container {
                padding: 20px;
                margin-top: 10px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            #reader {
                max-width: 100%;
            }
            
            #result {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('dashboard') }}" class="back-button">←</a>

    <div class="container">
        <div class="header">
            <h1>📷 Scan QR Code Absensi</h1>
            <div class="kegiatan">{{ $kegiatan->nama_kegiatan }}</div>
        </div>

        <div class="scanner-container">
            <div id="reader"></div>
            <div id="result"></div>
        </div>

        <div class="instructions">
            <h3>📋 Petunjuk Penggunaan:</h3>
            <p>Arahkan kamera ke QR code peserta untuk melakukan absensi. Pastikan QR code berada dalam frame yang terlihat dan pencahayaan cukup.</p>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const resultContainer = document.getElementById('result');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const kegiatanId = {{ $kegiatan->id }};

        function onScanSuccess(decodedText, decodedResult) {
            // Hentikan pemindaian setelah berhasil
            html5QrcodeScanner.pause();

            // Tampilkan pesan loading
            resultContainer.innerHTML = `<span class="status-indicator status-loading"></span>Memproses: ${decodedText}...`;
            resultContainer.className = 'loading';

            // Kirim data ke server
            fetch('{{ route("absensi.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    barcode: decodedText,
                    kegiatan_id: kegiatanId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultContainer.innerHTML = `<span class="status-indicator status-success"></span>${data.message}`;
                    resultContainer.className = 'success';
                } else {
                    resultContainer.innerHTML = `<span class="status-indicator status-error"></span>${data.message || 'Terjadi kesalahan.'}`;
                    resultContainer.className = 'error';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.innerHTML = `<span class="status-indicator status-error"></span>Error: Tidak dapat terhubung ke server.`;
                resultContainer.className = 'error';
            })
            .finally(() => {
                // Lanjutkan pemindaian setelah 3 detik
                setTimeout(() => {
                    resultContainer.innerHTML = '';
                    resultContainer.className = '';
                    html5QrcodeScanner.resume();
                }, 3000);
            });
        }

        function onScanError(errorMessage) {
            // Abaikan error, pemindaian akan terus berjalan
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                rememberLastUsedCamera: true
            }, 
            /* verbose= */ false);
        
        html5QrcodeScanner.render(onScanSuccess, onScanError);

    </script>
</body>
</html>