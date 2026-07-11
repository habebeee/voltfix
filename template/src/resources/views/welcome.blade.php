<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} — Servis Elektronik Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        .gradient-text {
            background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-bg {
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(59,130,246,0.12) 0%, transparent 70%),
                        radial-gradient(ellipse 40% 30% at 85% 40%, rgba(124,58,237,0.08) 0%, transparent 60%),
                        #ffffff;
        }

        /* Step connector line */
        .step-connector::after {
            content: '';
            position: absolute;
            top: 32px;
            left: calc(50% + 56px);
            right: calc(-50% + 56px);
            height: 2px;
            background: linear-gradient(90deg, #3B82F6, #7C3AED);
            opacity: 0.25;
        }

        /* Service card hover */
        .service-card {
            transition: transform 0.3s cubic-bezier(.4,0,.2,1), box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .service-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 60%, rgba(59,130,246,0.04) 100%);
            transition: opacity 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px -8px rgba(59,130,246,0.18);
        }
        .service-card:hover::before { opacity: 1; }

        /* Stat card pulse */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .float-1 { animation: float 4s ease-in-out infinite; }
        .float-2 { animation: float 4s ease-in-out infinite 1.3s; }
        .float-3 { animation: float 4s ease-in-out infinite 2.6s; }

        /* Shine on hero badge */
        @keyframes shine {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .shine-badge {
            background: linear-gradient(90deg, rgba(59,130,246,0.12) 0%, rgba(59,130,246,0.25) 40%, rgba(124,58,237,0.15) 60%, rgba(59,130,246,0.12) 100%);
            background-size: 200% auto;
            animation: shine 3s linear infinite;
        }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    {{-- ══════════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════════ --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100/80 shadow-sm shadow-gray-100/50">
        <div class="max-w-6xl mx-auto px-5 sm:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    @if($images['logo'])
                        <img src="{{ $images['logo'] }}" alt="{{ $siteName }}" class="w-8 h-8 rounded-xl object-contain">
                    @else
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-200 group-hover:shadow-blue-300 transition-shadow">
                            <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @endif
                    <span class="font-bold text-gray-900 text-lg tracking-tight">{{ $siteName }}</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-all">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-xl shadow-md shadow-blue-200/70 hover:shadow-blue-300/70 transition-all">
                        Daftar Gratis
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════════════════════════════════════
         HERO
    ══════════════════════════════════════════ --}}
    <section class="hero-bg pt-28 pb-20 px-5">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 {{ $images['hero_image'] ? 'lg:grid-cols-2' : '' }} gap-12 items-center mb-14">
                <div class="text-center {{ $images['hero_image'] ? 'lg:text-left' : 'max-w-3xl mx-auto' }}">
                    <div class="inline-flex items-center gap-2 shine-badge text-blue-700 text-xs font-semibold px-4 py-2 rounded-full mb-6 border border-blue-100">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                        Servis Profesional & Terpercaya · Garansi Kepuasan
                    </div>
                    <h1 class="text-5xl md:text-6xl font-black text-gray-900 leading-[1.1] tracking-tight mb-5">
                        Servis Elektronik<br>
                        <span class="gradient-text">Cepat & Transparan</span>
                    </h1>
                    <p class="text-lg text-gray-500 leading-relaxed mb-10 {{ $images['hero_image'] ? '' : 'max-w-xl mx-auto' }}">
                        Kulkas, TV, atau mesin cuci rusak? Ajukan dari rumah, pantau progres realtime, dan teknisi berpengalaman kami siap membantu.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 {{ $images['hero_image'] ? 'justify-start' : 'justify-center' }}">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-8 py-3.5 rounded-2xl text-base shadow-xl shadow-blue-200 hover:shadow-blue-300 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ajukan Servis Sekarang
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 hover:border-blue-200 hover:bg-blue-50/50 text-gray-700 font-semibold px-8 py-3.5 rounded-2xl text-base transition-all">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Cek Status Tiket
                        </a>
                    </div>
                </div>

                @if($images['hero_image'])
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl blur-2xl opacity-60"></div>
                    <img src="{{ $images['hero_image'] }}" alt="Servis Elektronik {{ $siteName }}"
                         class="relative w-full rounded-3xl shadow-2xl shadow-blue-200/50 object-cover aspect-[4/3]">
                </div>
                @endif
            </div>

            {{-- Trust stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto">
                @foreach([
                    ['num' => '500+',  'label' => 'Servis Selesai',    'color' => 'blue',   'class' => 'float-1'],
                    ['num' => '4.9★',  'label' => 'Rating Pelanggan',  'color' => 'yellow', 'class' => 'float-2'],
                    ['num' => '3',     'label' => 'Jenis Elektronik',  'color' => 'purple', 'class' => 'float-3'],
                    ['num' => '<24h',  'label' => 'Respon Admin',       'color' => 'green',  'class' => 'float-1'],
                ] as $stat)
                <div class="{{ $stat['class'] }} bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <div class="text-2xl font-black text-{{ $stat['color'] === 'yellow' ? 'amber' : $stat['color'] }}-500 mb-0.5">{{ $stat['num'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         CARA KERJA — redesigned elegant
    ══════════════════════════════════════════ --}}
    <section class="py-24 px-5 bg-white relative">
        <div class="max-w-6xl mx-auto">

            {{-- Section header --}}
            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-3 block">Mudah & Cepat</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Cara Kerja Voltfix</h2>
                <p class="text-gray-400 text-base max-w-md mx-auto">3 langkah mudah, masalah elektronik Anda beres hari ini</p>
            </div>

            {{-- Steps --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">

                {{-- Connector line (desktop) --}}
                <div class="hidden md:block absolute top-14 left-1/3 right-1/3 h-px bg-gradient-to-r from-blue-200 via-indigo-300 to-purple-200 z-0"></div>

                @php
                $steps = [
                    [
                        'n' => '01',
                        'title' => 'Ajukan Tiket',
                        'desc' => 'Isi formulir singkat, upload foto kerusakan, dan pilih jadwal kunjungan. Selesai dalam 2 menit.',
                        'from' => 'from-blue-500',
                        'to'   => 'to-blue-600',
                        'shadow' => 'shadow-blue-200',
                        'ring' => 'ring-blue-100',
                        'image' => $images['step_1_image'],
                        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M12 11h4m-4 4h2"/>',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Teknisi Datang',
                        'desc' => 'Admin pilih teknisi terbaik sesuai keahlian via Skill Matching. Teknisi tiba tepat waktu di lokasi Anda.',
                        'from' => 'from-violet-500',
                        'to'   => 'to-purple-600',
                        'shadow' => 'shadow-purple-200',
                        'ring' => 'ring-purple-100',
                        'image' => $images['step_2_image'],
                        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    ],
                    [
                        'n' => '03',
                        'title' => 'Selesai & Rating',
                        'desc' => 'Terima notifikasi WhatsApp saat servis selesai. Beri rating teknisi dan elektronik Anda kembali normal.',
                        'from' => 'from-emerald-500',
                        'to'   => 'to-teal-600',
                        'shadow' => 'shadow-emerald-200',
                        'ring' => 'ring-emerald-100',
                        'image' => $images['step_3_image'],
                        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    ],
                ];
                @endphp

                @foreach($steps as $i => $step)
                <div class="relative z-10 group">
                    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-lg shadow-gray-100/80 hover:shadow-xl hover:shadow-gray-200/60 transition-all duration-300 hover:-translate-y-1 text-center">

                        {{-- Step number badge --}}
                        <div class="absolute -top-3.5 left-8">
                            <span class="bg-gradient-to-r {{ $step['from'] }} {{ $step['to'] }} text-white text-xs font-black px-3 py-1 rounded-full shadow-md {{ $step['shadow'] }} tracking-widest">
                                {{ $step['n'] }}
                            </span>
                        </div>

                        {{-- Icon / Image --}}
                        @if($step['image'])
                            <div class="mb-5 mx-auto">
                                <img src="{{ $step['image'] }}" alt="{{ $step['title'] }}"
                                     class="w-24 h-24 rounded-2xl object-cover shadow-xl {{ $step['shadow'] }} ring-4 {{ $step['ring'] }} mx-auto">
                            </div>
                        @else
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br {{ $step['from'] }} {{ $step['to'] }} shadow-xl {{ $step['shadow'] }} mb-5 ring-4 {{ $step['ring'] }} mx-auto">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $step['svg'] !!}</svg>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>

                        {{-- Arrow connector (mobile) --}}
                        @if($i < 2)
                        <div class="md:hidden flex justify-center mt-6 mb-2">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         LAYANAN — redesigned elegant
    ══════════════════════════════════════════ --}}
    <section class="py-24 px-5" style="background: linear-gradient(180deg, #F8FAFF 0%, #F0F4FF 100%);">
        <div class="max-w-6xl mx-auto">

            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-3 block">Spesialisasi Kami</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Layanan Servis</h2>
                <p class="text-gray-400 max-w-md mx-auto">Teknisi bersertifikat dengan pengalaman bertahun-tahun menangani elektronik rumah tangga</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kulkas --}}
                <div class="service-card bg-white rounded-3xl border border-blue-100/60 shadow-lg shadow-blue-50 p-8">
                    <div class="flex items-start justify-between mb-6">
                        @if($images['service_kulkas_image'])
                            <img src="{{ $images['service_kulkas_image'] }}" alt="Kulkas & Freezer"
                                 class="w-14 h-14 rounded-2xl object-cover border border-blue-200/60">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200/60 flex items-center justify-center">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 6a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6zM3 10h18M8 14h.01M8 17h.01"/>
                                </svg>
                            </div>
                        @endif
                        <span class="text-xs font-semibold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full border border-blue-100">KULKAS</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kulkas & Freezer</h3>
                    <p class="text-sm text-gray-400 mb-5 leading-relaxed">Servis semua merek kulkas & freezer dengan teknisi berpengalaman minimum 5 tahun.</p>
                    <ul class="space-y-2.5">
                        @foreach(['Tidak dingin / kurang dingin', 'Kompresor rusak', 'Bocor freon', 'Bunyi berisik', 'Pintu tidak rapat'] as $item)
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <div class="w-4.5 h-4.5 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0" style="width:18px;height:18px">
                                <svg class="w-2.5 h-2.5 text-blue-600" style="width:10px;height:10px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- TV — middle card with accent --}}
                <div class="service-card bg-gradient-to-b from-indigo-600 to-violet-700 rounded-3xl shadow-2xl shadow-indigo-200 p-8 relative overflow-hidden">
                    {{-- Decorative circles --}}
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/5 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/5 rounded-full"></div>

                    <div class="flex items-start justify-between mb-6 relative">
                        @if($images['service_tv_image'])
                            <img src="{{ $images['service_tv_image'] }}" alt="TV & Monitor"
                                 class="w-14 h-14 rounded-2xl object-cover border border-white/20">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur border border-white/20 flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <span class="text-xs font-semibold bg-white/20 text-white px-3 py-1.5 rounded-full border border-white/20">TV</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 relative">TV & Monitor</h3>
                    <p class="text-sm text-indigo-200 mb-5 leading-relaxed relative">Ahli perbaikan TV semua merek & ukuran, termasuk TV LED, OLED, dan Smart TV.</p>
                    <ul class="space-y-2.5 relative">
                        @foreach(['Layar mati / gelap', 'Gambar bergaris', 'Suara tidak ada', 'Remote tidak respon', 'Lampu backlight mati'] as $item)
                        <li class="flex items-center gap-2.5 text-sm text-indigo-100">
                            <div class="w-4.5 h-4.5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0" style="width:18px;height:18px">
                                <svg class="w-2.5 h-2.5 text-white" style="width:10px;height:10px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Mesin Cuci --}}
                <div class="service-card bg-white rounded-3xl border border-teal-100/60 shadow-lg shadow-teal-50 p-8">
                    <div class="flex items-start justify-between mb-6">
                        @if($images['service_mesin_cuci_image'])
                            <img src="{{ $images['service_mesin_cuci_image'] }}" alt="Mesin Cuci"
                                 class="w-14 h-14 rounded-2xl object-cover border border-teal-200/60">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-50 to-emerald-100 border border-teal-200/60 flex items-center justify-center">
                                <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm8 4a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                            </div>
                        @endif
                        <span class="text-xs font-semibold bg-teal-50 text-teal-600 px-3 py-1.5 rounded-full border border-teal-100">MESIN CUCI</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Mesin Cuci</h3>
                    <p class="text-sm text-gray-400 mb-5 leading-relaxed">Perbaikan mesin cuci top loading & front loading semua merek dengan garansi servis.</p>
                    <ul class="space-y-2.5">
                        @foreach(['Tidak berputar', 'Bocor air', 'Tidak menyala', 'Error code', 'Pengering rusak'] as $item)
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <div class="w-4.5 h-4.5 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0" style="width:18px;height:18px">
                                <svg class="w-2.5 h-2.5 text-teal-600" style="width:10px;height:10px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         CTA BANNER
    ══════════════════════════════════════════ --}}
    <section class="py-20 px-5 relative overflow-hidden">
        @if($images['cta_background_image'])
            <div class="absolute inset-0">
                <img src="{{ $images['cta_background_image'] }}" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/80 via-indigo-700/80 to-violet-800/80"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-700 to-violet-800"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.06) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(255,255,255,0.04) 0%, transparent 50%)"></div>
        @endif
        <div class="max-w-4xl mx-auto text-center relative">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-xs font-semibold px-4 py-2 rounded-full mb-6">
                ⚡ Mulai sekarang — gratis
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-white mb-5 leading-tight">
                Elektronik Rusak?<br>
                <span class="text-blue-200">Jangan Biarkan Menunggu!</span>
            </h2>
            <p class="text-blue-100 text-lg mb-10 max-w-md mx-auto">
                Daftar gratis dan ajukan servis dalam 2 menit. Teknisi terbaik kami siap datang ke rumah Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold px-10 py-4 rounded-2xl hover:bg-blue-50 transition-colors text-base shadow-2xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white/10 border border-white/25 text-white font-semibold px-8 py-4 rounded-2xl hover:bg-white/20 transition-colors text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Sudah punya akun
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <footer class="bg-gray-950 text-gray-500 py-10 px-5">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                @if($images['logo'])
                    <img src="{{ $images['logo'] }}" alt="{{ $siteName }}" class="w-7 h-7 rounded-lg object-contain">
                @else
                    <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                @endif
                <span class="text-white font-semibold">{{ $siteName }}</span>
                <span class="text-gray-600">·</span>
                <span class="text-xs">Servis Elektronik Terpercaya</span>
            </div>
            <p class="text-xs">&copy; {{ date('Y') }} {{ $siteName }}. Semua hak dilindungi.</p>
            <div class="flex gap-4 text-xs">
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar</a>
            </div>
        </div>
    </footer>

</body>
</html>
