<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} — Servis Laptop, HP & TV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        display: ['Bricolage Grotesque', 'Plus Jakarta Sans', 'sans-serif'],
                        service: ['Outfit', 'Plus Jakarta Sans', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.42);
            --glass-bg-strong: rgba(255, 255, 255, 0.58);
            --glass-border: rgba(255, 255, 255, 0.55);
            --glass-edge: rgba(255, 255, 255, 0.75);
            --glass-shadow: 0 8px 32px rgba(15, 23, 42, 0.08),
                            inset 0 1px 0 rgba(255, 255, 255, 0.65),
                            inset 0 -1px 0 rgba(255, 255, 255, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #1e293b;
            min-height: 100vh;
            background:
                radial-gradient(ellipse 80% 60% at 10% -10%, rgba(251, 146, 60, 0.35) 0%, transparent 55%),
                radial-gradient(ellipse 70% 50% at 95% 5%, rgba(56, 189, 248, 0.28) 0%, transparent 50%),
                radial-gradient(ellipse 60% 45% at 50% 100%, rgba(167, 139, 250, 0.22) 0%, transparent 55%),
                radial-gradient(ellipse 40% 30% at 70% 40%, rgba(251, 191, 36, 0.12) 0%, transparent 50%),
                linear-gradient(160deg, #f0f4f8 0%, #e8eef5 40%, #f5f0eb 100%);
            background-attachment: fixed;
        }

        .brand-name, h1, h2 { font-family: 'Bricolage Grotesque', 'Plus Jakarta Sans', sans-serif; }

        /* Liquid glass panels */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            position: relative;
            overflow: hidden;
        }
        .glass::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(
                135deg,
                rgba(255,255,255,0.45) 0%,
                rgba(255,255,255,0.08) 40%,
                transparent 60%,
                rgba(255,255,255,0.12) 100%
            );
            pointer-events: none;
            z-index: 0;
        }
        .glass > * { position: relative; z-index: 1; }

        .glass-strong {
            background: var(--glass-bg-strong);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid var(--glass-edge);
            box-shadow: var(--glass-shadow);
            position: relative;
            overflow: hidden;
        }
        .glass-strong::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(
                145deg,
                rgba(255,255,255,0.55) 0%,
                rgba(255,255,255,0.1) 45%,
                transparent 70%
            );
            pointer-events: none;
            z-index: 0;
        }
        .glass-strong > * { position: relative; z-index: 1; }

        .glass-nav {
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.04),
                        inset 0 -1px 0 rgba(255, 255, 255, 0.4);
        }

        .glass-btn {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.8),
                        0 2px 8px rgba(15, 23, 42, 0.06);
            transition: all .2s ease;
        }
        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.75);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.9),
                        0 4px 14px rgba(15, 23, 42, 0.1);
            transform: translateY(-1px);
        }

        .glass-btn-primary {
            background: linear-gradient(135deg, rgba(249,115,22,0.92), rgba(245,158,11,0.88));
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.35),
                        0 8px 24px rgba(249, 115, 22, 0.3);
            transition: all .2s ease;
        }
        .glass-btn-primary:hover {
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.45),
                        0 10px 28px rgba(249, 115, 22, 0.4);
            transform: translateY(-1px);
        }

        .glass-stat {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(16px) saturate(160%);
            -webkit-backdrop-filter: blur(16px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.6),
                        0 4px 16px rgba(15, 23, 42, 0.05);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .glass-stat:hover {
            transform: translateY(-2px);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.7),
                        0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .glass-pill {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            opacity: 0.55;
        }

        .step-photo {
            position: relative;
            width: 5.5rem;
            height: 5.5rem;
            border-radius: 1.1rem;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow:
                0 8px 20px -6px rgba(15, 23, 42, 0.18),
                inset 0 1px 0 rgba(255,255,255,0.5);
            border: 1.5px solid rgba(255,255,255,0.75);
        }
        .step-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(0.85) contrast(1.05);
            transition: transform .45s ease, filter .45s ease;
        }
        .step-photo:hover img {
            transform: scale(1.08);
            filter: saturate(1) contrast(1.05);
        }
        .step-photo::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(160deg, rgba(255,255,255,0.35) 0%, transparent 42%),
                linear-gradient(to top, rgba(15,23,42,0.55) 0%, transparent 55%),
                radial-gradient(circle at 80% 20%, rgba(249,115,22,0.25), transparent 50%);
            pointer-events: none;
        }
        .step-photo-ring {
            position: absolute;
            inset: -5px;
            border-radius: 1.35rem;
            background: linear-gradient(135deg, rgba(249,115,22,0.35), rgba(56,189,248,0.2), rgba(167,139,250,0.25));
            opacity: 0.7;
            z-index: -1;
            filter: blur(0.5px);
        }
        .step-card {
            background: rgba(255,255,255,0.28);
            border: 1px solid rgba(255,255,255,0.45);
            border-radius: 1rem;
            padding: 0.875rem 1rem;
            transition: background .2s ease, transform .2s ease;
        }
        .step-card:hover {
            background: rgba(255,255,255,0.45);
            transform: translateX(2px);
        }

        /* Tipografi section Layanan */
        .services-title {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }
        .services-subtitle {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-weight: 400;
            letter-spacing: 0.01em;
        }
        h2.services-title {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }
        .service-card-title {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.025em;
            font-size: 1.125rem;
            line-height: 1.2;
        }
        .service-card-brands {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            letter-spacing: 0.02em;
            font-size: 0.75rem;
        }
        .service-item-text {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            letter-spacing: 0.005em;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    {{-- Floating orbs for liquid depth --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10" aria-hidden="true">
        <div class="orb w-72 h-72 bg-orange-300/40 top-20 -left-20"></div>
        <div class="orb w-96 h-96 bg-sky-300/30 top-40 -right-32"></div>
        <div class="orb w-64 h-64 bg-violet-300/35 bottom-32 left-1/3"></div>
    </div>

    {{-- NAVBAR --}}
    <header class="glass-nav sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center justify-between h-14">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if($images['logo'])
                    <img src="{{ $images['logo'] }}" alt="{{ $siteName }}" class="w-7 h-7 object-contain drop-shadow-sm">
                @else
                    <div class="w-7 h-7 bg-gradient-to-br from-orange-500 to-amber-400 rounded-lg flex items-center justify-center shadow-md shadow-orange-200/60 border border-white/40">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                @endif
                <span class="brand-name text-slate-900 text-[15px] font-bold">{{ $siteName }}</span>
            </a>
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-white/40 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="glass-btn-primary text-white font-semibold px-4 py-1.5 rounded-xl">
                    Daftar
                </a>
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14 sm:py-20">
            <div class="glass-strong rounded-3xl p-8 sm:p-10 lg:p-12">
                <div class="grid grid-cols-1 {{ $images['hero_image'] ? 'lg:grid-cols-2' : '' }} gap-10 items-center">
                    <div>
                        <p class="glass-pill inline-flex items-center gap-1.5 text-sm text-orange-800 font-medium px-3 py-1 rounded-full mb-5">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                            Kab. Tangerang · Servis ke rumah & kantor
                        </p>
                        <h1 class="text-3xl sm:text-4xl lg:text-[2.75rem] font-bold text-slate-900 leading-tight mb-4">
                            Servis laptop, HP,<br>dan TV yang bisa<br>
                            <span class="text-orange-600">dipantau online.</span>
                        </h1>
                        <p class="text-slate-600 leading-relaxed mb-8 max-w-md">
                            Ajukan tiket, upload foto kerusakan, dan tunggu teknisi datang. Status servis bisa dicek kapan saja lewat akun Anda.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}"
                               class="glass-btn-primary inline-flex items-center gap-2 text-white font-semibold px-5 py-2.5 rounded-xl">
                                Ajukan Servis
                            </a>
                            <a href="{{ route('login') }}"
                               class="glass-btn inline-flex items-center gap-2 text-slate-700 font-medium px-5 py-2.5 rounded-xl">
                                Cek Status Tiket
                            </a>
                        </div>
                    </div>

                    @if($images['hero_image'])
                    <div class="relative group">
                        <div class="absolute -inset-3 rounded-2xl bg-gradient-to-br from-orange-200/50 via-sky-200/30 to-violet-200/40 blur-xl"></div>
                        <div class="relative rounded-2xl overflow-hidden border border-white/70 shadow-xl shadow-slate-300/40 aspect-[4/3] ring-2 ring-amber-300/50">
                            <img src="{{ $images['hero_image'] }}" alt="Servis {{ $siteName }}"
                                 class="w-full h-full object-cover scale-[1.02] group-hover:scale-105 transition-transform duration-700">

                            {{-- Cover / glass overlay + soft yellow wash --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/15 to-amber-300/20 pointer-events-none"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-amber-200/25 via-transparent to-transparent pointer-events-none"></div>

                            {{-- Bottom caption chip --}}
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <div class="glass-strong rounded-xl px-3.5 py-2.5 flex items-center gap-3 border-white/50">
                                    <div class="w-8 h-8 rounded-lg bg-orange-500/90 flex items-center justify-center flex-shrink-0 shadow-md shadow-orange-500/30">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 leading-tight inline-flex items-center gap-1">
                                            Teknisi bersertifikasi
                                            <svg class="w-3.5 h-3.5 text-[#1D9BF0] flex-shrink-0" viewBox="0 0 22 22" aria-label="Terverifikasi" role="img">
                                                <path fill="currentColor" d="M20.396 11c-.018-.646-.215-1.275-.57-1.816-.354-.54-.852-.972-1.438-1.246.223-.607.27-1.264.14-1.897-.131-.634-.437-1.218-.878-1.683-.44-.466-.998-.788-1.612-.904-.614-.117-1.246-.044-1.821.213-.574.257-1.06.676-1.397 1.203-.338-.527-.823-.946-1.397-1.203-.575-.257-1.207-.33-1.821-.213-.614.116-1.172.438-1.612.904-.44.465-.747 1.05-.878 1.683-.13.633-.083 1.29.14 1.897-.586.274-1.084.706-1.438 1.246-.355.541-.552 1.17-.57 1.816.018.646.215 1.275.57 1.816.354.54.852.972 1.438 1.246-.223.607-.27 1.264-.14 1.897.131.634.437 1.218.878 1.683.44.466.998.788 1.612.904.614.117 1.246.044 1.821-.213.574-.257 1.06-.676 1.397-1.203.338.527.823.946 1.397 1.203.575.257 1.207.33 1.821.213.614-.116 1.172-.438 1.612-.904.44-.465.747-1.05.878-1.683.13-.633.083-1.29-.14-1.897.586-.274 1.084-.706 1.438-1.246.355-.541.552-1.17.57-1.816zm-10.878 3.23l-3.214-3.22 1.42-1.418 1.793 1.79 4.553-4.553 1.42 1.42-5.972 5.98z"/>
                                            </svg>
                                        </p>
                                        <p class="text-[11px] text-slate-600 truncate">Siap datang ke rumah & kantor Anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Stats strip --}}
                <div class="mt-10 pt-8 border-t border-white/40 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach([
                        [
                            'num' => '500+',
                            'label' => 'Servis selesai',
                            'icon_bg' => 'bg-emerald-100 text-emerald-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        [
                            'num' => '4.9',
                            'label' => 'Rating pelanggan',
                            'icon_bg' => 'bg-amber-100 text-amber-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
                        ],
                        [
                            'num' => '3',
                            'label' => 'Kategori servis',
                            'icon_bg' => 'bg-violet-100 text-violet-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>',
                        ],
                        [
                            'num' => '<24 jam',
                            'label' => 'Respon admin',
                            'icon_bg' => 'bg-sky-100 text-sky-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                    ] as $stat)
                    <div class="glass-stat rounded-2xl px-4 py-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl {{ $stat['icon_bg'] }} flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-4.5 h-4.5 w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-2xl font-bold text-slate-900 leading-none">{{ $stat['num'] }}</p>
                                <p class="text-sm text-slate-500 mt-1.5 leading-snug">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="glass rounded-3xl p-8 sm:p-10 relative overflow-hidden">
                {{-- Dekorasi background --}}
                <div class="absolute -top-20 -right-16 w-56 h-56 rounded-full bg-orange-300/25 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-12 w-64 h-64 rounded-full bg-sky-300/20 blur-3xl pointer-events-none"></div>
                <div class="absolute top-1/2 right-8 w-32 h-32 rounded-full bg-violet-300/15 blur-2xl pointer-events-none hidden sm:block"></div>
                {{-- Soft pattern dots --}}
                <div class="absolute inset-0 opacity-[0.035] pointer-events-none"
                     style="background-image: radial-gradient(#0f172a 1px, transparent 1px); background-size: 18px 18px;"></div>

                <div class="relative">
                    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
                        <div>
                            <p class="glass-pill inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-orange-700 px-2.5 py-1 rounded-full mb-3">
                                Alur servis
                            </p>
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1">Cara kerjanya</h2>
                            <p class="text-slate-500 text-sm">Tiga langkah, tanpa ribet.</p>
                        </div>
                        <div class="hidden sm:flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                            <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-[11px] font-bold flex items-center justify-center">1</span>
                            <svg class="w-3 h-3 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-[11px] font-bold flex items-center justify-center">2</span>
                            <svg class="w-3 h-3 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-[11px] font-bold flex items-center justify-center">3</span>
                        </div>
                    </div>

                    <div>
                        @php
                        $steps = [
                            [
                                'n' => '1',
                                'title' => 'Ajukan tiket',
                                'desc' => 'Daftar akun, isi detail kerusakan, upload foto, dan tentukan jadwal kunjungan.',
                                'image' => $images['step_1_image'],
                                'accent' => 'from-orange-400 to-amber-400',
                                'badge' => 'bg-orange-500',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                            ],
                            [
                                'n' => '2',
                                'title' => 'Teknisi ditugaskan',
                                'desc' => 'Admin memilih teknisi sesuai keahlian (laptop, HP, atau TV) lalu teknisi datang ke lokasi Anda.',
                                'image' => $images['step_2_image'],
                                'accent' => 'from-sky-400 to-cyan-400',
                                'badge' => 'bg-sky-500',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                            ],
                            [
                                'n' => '3',
                                'title' => 'Selesai & beri rating',
                                'desc' => 'Dapat notifikasi WhatsApp saat servis selesai. Beri rating untuk teknisi yang mengerjakan.',
                                'image' => $images['step_3_image'],
                                'accent' => 'from-violet-400 to-fuchsia-400',
                                'badge' => 'bg-violet-500',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
                            ],
                        ];
                        @endphp

                        @foreach($steps as $i => $step)
                        <div class="flex gap-5 sm:gap-6">
                            {{-- Number + connector --}}
                            <div class="flex flex-col items-center flex-shrink-0 w-9">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $step['accent'] }} text-white font-bold text-sm flex items-center justify-center z-10 shadow-md border border-white/50">
                                    {{ $step['n'] }}
                                </div>
                                @if($i < count($steps) - 1)
                                <div class="flex flex-col items-center flex-1 py-1.5 min-h-[32px]">
                                    <div class="w-px flex-1 bg-gradient-to-b from-orange-300 via-sky-200 to-violet-200"></div>
                                    <svg class="w-3.5 h-3.5 text-orange-400 -mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0 pb-6 {{ $i < count($steps) - 1 ? '' : 'pb-0' }}">
                                <div class="step-card flex gap-4 items-start">
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <h3 class="font-semibold text-slate-900 mb-1">{{ $step['title'] }}</h3>
                                        <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                                    </div>

                                    @if($step['image'])
                                    <div class="hidden sm:block relative flex-shrink-0">
                                        <div class="step-photo-ring"></div>
                                        <div class="step-photo">
                                            <img src="{{ $step['image'] }}" alt="{{ $step['title'] }}">
                                            {{-- Silhouette badge --}}
                                            <div class="absolute bottom-1.5 right-1.5 w-6 h-6 rounded-lg {{ $step['badge'] }} flex items-center justify-center shadow-md border border-white/40 z-10">
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="hidden sm:flex relative flex-shrink-0 w-[5.5rem] h-[5.5rem] rounded-[1.1rem] bg-gradient-to-br {{ $step['accent'] }} items-center justify-center opacity-90 shadow-md border border-white/40">
                                        <svg class="w-7 h-7 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LAYANAN --}}
    <section class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="mb-8">
                <p class="glass-pill inline-flex text-[11px] font-semibold uppercase tracking-[0.12em] text-orange-700 px-2.5 py-1 rounded-full mb-3 font-service">
                    Spesialisasi
                </p>
                <h2 class="services-title text-2xl sm:text-3xl text-slate-900 mb-2">Layanan kami</h2>
                <p class="services-subtitle text-slate-500 text-[15px]">Spesialis per kategori, bukan general repair.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- HP --}}
                <div class="glass rounded-2xl p-6 border-t-2 border-t-amber-400/80 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-center gap-3 mb-5">
                        @if($images['service_hp_image'])
                            <img src="{{ $images['service_hp_image'] }}" alt="HP" class="w-11 h-11 rounded-xl object-cover border border-white/50 shadow-sm">
                        @else
                            <div class="w-11 h-11 rounded-xl glass-btn flex items-center justify-center text-lg">📱</div>
                        @endif
                        <div>
                            <h3 class="service-card-title text-slate-900">HP & Smartphone</h3>
                            <p class="service-card-brands text-amber-700/70 mt-0.5">iPhone, Samsung, Xiaomi, dll.</p>
                        </div>
                    </div>
                    <ul class="space-y-2">
                        @foreach(['Layar retak / tidak responsif', 'Baterai drop', 'Bootloop & software', 'Charging port rusak'] as $item)
                        <li class="flex gap-2.5 glass-stat rounded-xl px-3.5 py-2 items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 flex-shrink-0"></span>
                            <span class="service-item-text text-slate-700">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- TV --}}
                <div class="glass rounded-2xl p-6 border-t-2 border-t-violet-500/80 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-center gap-3 mb-5">
                        @if($images['service_tv_image'])
                            <img src="{{ $images['service_tv_image'] }}" alt="TV" class="w-11 h-11 rounded-xl object-cover border border-white/50 shadow-sm">
                        @else
                            <div class="w-11 h-11 rounded-xl glass-btn flex items-center justify-center text-lg">📺</div>
                        @endif
                        <div>
                            <h3 class="service-card-title text-slate-900">TV & Monitor</h3>
                            <p class="service-card-brands text-violet-700/70 mt-0.5">Smart TV, LED, monitor kantor</p>
                        </div>
                    </div>
                    <ul class="space-y-2">
                        @foreach(['Layar mati / gelap', 'Gambar bergaris', 'Suara tidak keluar', 'Backlight mati'] as $item)
                        <li class="flex gap-2.5 glass-stat rounded-xl px-3.5 py-2 items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-violet-400 flex-shrink-0"></span>
                            <span class="service-item-text text-slate-700">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Laptop --}}
                <div class="glass rounded-2xl p-6 border-t-2 border-t-sky-500/80 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-center gap-3 mb-5">
                        @if($images['service_laptop_image'])
                            <img src="{{ $images['service_laptop_image'] }}" alt="Laptop" class="w-11 h-11 rounded-xl object-cover border border-white/50 shadow-sm">
                        @else
                            <div class="w-11 h-11 rounded-xl glass-btn flex items-center justify-center text-lg">💻</div>
                        @endif
                        <div>
                            <h3 class="service-card-title text-slate-900">Laptop</h3>
                            <p class="service-card-brands text-sky-700/70 mt-0.5">MacBook, Asus, Lenovo, dll.</p>
                        </div>
                    </div>
                    <ul class="space-y-2">
                        @foreach(['Layar hitam', 'Keyboard rusak', 'Overheat / mati sendiri', 'Install ulang OS'] as $item)
                        <li class="flex gap-2.5 glass-stat rounded-xl px-3.5 py-2 items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400 flex-shrink-0"></span>
                            <span class="service-item-text text-slate-700">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="glass-strong rounded-3xl p-8 sm:p-10 relative overflow-hidden">
                @if($images['cta_background_image'])
                    <img src="{{ $images['cta_background_image'] }}" alt=""
                         class="absolute inset-0 w-full h-full object-cover opacity-[0.08]">
                @endif
                <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-orange-300/30 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-40 h-40 rounded-full bg-sky-300/25 blur-3xl pointer-events-none"></div>

                <div class="relative max-w-lg">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">
                        Butuh servis sekarang?
                    </h2>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Buat akun gratis, ajukan tiket dalam beberapa menit. Teknisi kami melayani area Kab. Tangerang.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}"
                           class="glass-btn-primary text-white font-semibold px-5 py-2.5 rounded-xl text-sm">
                            Daftar & Ajukan Servis
                        </a>
                        <a href="{{ route('login') }}"
                           class="glass-btn text-slate-700 font-medium px-5 py-2.5 rounded-xl text-sm">
                            Sudah punya akun →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-8 mt-4">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="glass rounded-2xl px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-slate-500">
                <div class="flex items-center gap-2 flex-wrap">
                    @if($images['logo'])
                        <img src="{{ $images['logo'] }}" alt="{{ $siteName }}" class="w-5 h-5 object-contain">
                    @endif
                    <span class="brand-name text-slate-800 font-bold">{{ $siteName }}</span>
                    <span class="text-slate-300">·</span>
                    <span>Servis laptop, HP & TV · Kab. Tangerang</span>
                </div>
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} {{ $siteName }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
