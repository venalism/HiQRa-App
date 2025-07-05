<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Peserta - {{ $peserta->nama }}</title>
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; background-color: #f0f2f5; margin: 0; }
        .qr-card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #333; margin-top: 0; }
        p { color: #555; }
        .qr-code { margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="qr-card">
        <h1>{{ $peserta->nama }}</h1>
        <p>Silakan tunjukkan QR Code ini kepada panitia untuk melakukan absensi.</p>
        <div class="qr-code">
            {!! QrCode::size(250)->generate($peserta->barcode) !!}
        </div>
    </div>
</body>
</html>
