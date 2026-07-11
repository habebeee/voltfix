<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Terjadi Kesalahan Server</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">⚠️</div>
        <h1 class="text-4xl font-extrabold text-gray-900 mb-3">500</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Terjadi Kesalahan Server</h2>
        <p class="text-gray-500 mb-8">
            Maaf, terjadi kesalahan pada server kami. Tim kami sedang menangani masalah ini.
            Silakan coba lagi beberapa saat.
        </p>
        <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors inline-block">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
