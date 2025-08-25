<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login {{ ucfirst($url) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">
            Login {{ ucfirst($url) }}
        </h2>

        {{-- Tampilkan error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url("$url/login") }}">
            @csrf

            <div class="mb-4">
                <label for="npm" class="block text-gray-700 font-semibold">NPM</label>
                <input id="npm" type="text" name="npm" 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                       value="{{ old('npm') }}" required autofocus>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input id="password" type="password" name="password" 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                       required>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Login
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="/" class="text-sm text-gray-500 hover:underline">Kembali ke Home</a>
        </div>
    </div>

</body>
</html>