<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — VoltFix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        display: ['Bricolage Grotesque', 'Plus Jakarta Sans', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
            --font-display: 'Bricolage Grotesque', 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: var(--font-body);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(251,146,60,.15) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(56,189,248,.12) 0%, transparent 50%),
                #F8FAFC;
        }
        h1, h2, .font-display, .brand-name {
            font-family: var(--font-display);
            letter-spacing: -0.025em;
        }
        .brand-name { font-weight: 800; letter-spacing: -0.045em; }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <header class="sticky top-0 z-20">
        <div class="relative overflow-hidden border-b border-orange-200/60 bg-gradient-to-r from-orange-500 via-amber-500 to-orange-400 shadow-lg shadow-orange-500/20">
            <div class="absolute inset-0 opacity-30 pointer-events-none"
                 style="background-image:radial-gradient(circle at 15% 50%, rgba(255,255,255,.45) 0%, transparent 42%), radial-gradient(circle at 85% 20%, rgba(255,255,255,.25) 0%, transparent 35%);"></div>
            <div class="relative max-w-md mx-auto px-3 sm:px-4 h-13 py-2.5 flex items-center justify-between gap-3">
                <a href="{{ route('home') }}"
                   class="group inline-flex items-center gap-2.5 rounded-full bg-white/15 hover:bg-white/25 border border-white/30 backdrop-blur-sm pl-1.5 pr-3.5 py-1.5 transition-all duration-200 hover:-translate-x-0.5">
                    <span class="w-8 h-8 rounded-full bg-white text-orange-600 flex items-center justify-center shadow-md shadow-orange-900/10 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-[10px] uppercase tracking-[0.14em] text-white/75 font-semibold">Navigasi</span>
                        <span class="block text-sm font-bold text-white">Kembali ke beranda</span>
                    </span>
                </a>
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 text-white/90 text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    VoltFix
                </a>
            </div>
        </div>
    </header>

    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-orange-100 shadow-xl shadow-orange-100/50 p-8">
                <div class="flex justify-center mb-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center shadow-md shadow-orange-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="brand-name text-xl text-slate-900">VoltFix</span>
                    </a>
                </div>

                <h1 class="text-2xl font-extrabold text-slate-900 text-center mb-1">Masuk ke akun</h1>
                <p class="text-sm text-slate-500 text-center mb-6">Cek status tiket atau ajukan servis baru</p>

                @if(session('status'))
                    <div class="mb-4 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                        <p class="text-sm text-emerald-700">{{ session('status') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3">
                        @foreach($errors->all() as $error)
                            <p class="text-sm text-red-700">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border border-stone-200 bg-orange-50/30 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 focus:bg-white"
                               placeholder="email@contoh.com">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-slate-700">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-orange-600 hover:text-orange-700 font-medium">
                                Lupa password?
                            </a>
                        </div>
                        <input type="password" name="password" required
                               class="w-full border border-stone-200 bg-orange-50/30 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 focus:bg-white"
                               placeholder="Password Anda">
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-semibold py-2.5 rounded-xl shadow-md shadow-orange-200 transition-all text-sm">
                        Masuk
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-orange-50 text-center">
                    <p class="text-sm text-slate-500">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700 font-semibold">Daftar di sini</a>
                    </p>
                </div>
            </div>
            <p class="text-center text-xs text-slate-400 mt-4">Kabupaten Tangerang & sekitarnya</p>
        </div>
    </div>
</body>
</html>
