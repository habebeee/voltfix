<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">🔍</div>
        <h1 class="text-4xl font-extrabold text-gray-900 mb-3">404</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-8">
            Halaman yang Anda cari tidak ada atau telah dipindahkan.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url()->previous() }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-2.5 rounded-lg transition-colors">
                ← Kembali
            </a>
            <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
                Ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
