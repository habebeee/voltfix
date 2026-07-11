@extends('layouts.app')
@section('title', 'Ajukan Servis — Voltfix')

@push('head')
<style>
        .form-input {
            font-family: var(--font-body, 'Plus Jakarta Sans', system-ui, sans-serif);
            width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E5E7EB;
        border-radius: 12px;
        font-size: 14px;
        color: #111827;
        background: #FAFAFA;
        transition: all .2s;
        outline: none;
    }
    .form-input:focus {
        border-color: #F97316;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(249,115,22,.12);
    }
    .form-input::placeholder { color: #D1D5DB; }

    .form-section {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #F3F4F6;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        overflow: hidden;
        position: relative;
    }
    .form-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 1rem 1rem 0 0;
    }
    .form-section--cat::before   { background: linear-gradient(90deg, #0EA5E9, #7C3AED, #F59E0B); }
    .form-section--detail::before { background: linear-gradient(90deg, #EC4899, #F43F5E); }
    .form-section--addr::before  { background: linear-gradient(90deg, #F97316, #EF4444); }
    .form-section--sched::before { background: linear-gradient(90deg, #F59E0B, #D97706); }
    .form-section--photo::before { background: linear-gradient(90deg, #8B5CF6, #7C3AED); }

        .cat-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid #E5E7EB;
            background: #FAFAFA;
            cursor: pointer;
            transition: all .2s;
            user-select: none;
        }
        .cat-option:hover { border-color: #FDBA74; background: #FFF7ED; }
        .cat-option.active-hp {
            border-color: #F97316;
            background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
            box-shadow: 0 4px 14px -2px rgba(249,115,22,.2);
        }
        .cat-option.active-laptop {
            border-color: #0EA5E9;
            background: linear-gradient(135deg, #F0F9FF, #E0F2FE);
            box-shadow: 0 4px 14px -2px rgba(14,165,233,.2);
        }
        .cat-option.active-tv {
            border-color: #7C3AED;
            background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
            box-shadow: 0 4px 14px -2px rgba(124,58,237,.2);
        }
        .cat-option-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #fff;
            border: 1px solid rgba(0,0,0,.08);
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(15,23,42,.06);
        }
        .cat-option-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .cat-option-icon img.hidden,
        .cat-option-icon .cat-fallback.hidden {
            display: none !important;
        }
        .cat-option-icon .cat-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .time-chip {
            font-family: var(--font-body, 'Plus Jakarta Sans', system-ui, sans-serif);
            display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1.5px solid #E5E7EB;
        background: #FAFAFA;
        font-size: 13px;
        font-weight: 500;
        color: #4B5563;
        cursor: pointer;
        transition: all .2s;
        user-select: none;
    }
    .time-chip:hover { border-color: #FDBA74; background: #FFF7ED; }
    .time-chip.active {
        border-color: #F97316;
        background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
        color: #C2410C;
        font-weight: 600;
        box-shadow: 0 4px 12px -2px rgba(249,115,22,.2);
    }

    .damage-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-radius: 12px;
        border: 1.5px solid #E5E7EB;
        background: #FAFAFA;
        cursor: pointer;
        transition: all .2s;
        user-select: none;
    }
    .damage-option:hover { border-color: #F9A8D4; background: #FDF2F8; }
    .damage-option.active {
        border-color: #EC4899;
        background: linear-gradient(135deg, #FDF2F8, #FCE7F3);
        box-shadow: 0 4px 12px -2px rgba(236,72,153,.18);
    }
    .damage-option .radio-dot {
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 2px solid #D1D5DB;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: all .2s;
    }
    .damage-option.active .radio-dot {
        border-color: #EC4899;
        background: #EC4899;
    }

    .step-dot {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700;
        transition: all .3s;
        flex-shrink: 0;
    }
    .step-dot.done   { background: #F97316; color: #fff; box-shadow: 0 2px 8px rgba(249,115,22,.35); }
    .step-dot.active { background: #FFF7ED; color: #EA580C; border: 2px solid #F97316; }
    .step-dot.idle   { background: #F1F5F9; color: #94A3B8; }

    @keyframes fadeUp {
        from { opacity:0; transform: translateY(10px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .fade-up   { animation: fadeUp .35s ease both; }
    .fade-up-1 { animation-delay:.05s; }
    .fade-up-2 { animation-delay:.10s; }
    .fade-up-3 { animation-delay:.15s; }
    .fade-up-4 { animation-delay:.20s; }
    .fade-up-5 { animation-delay:.25s; }
    [x-cloak] { display: none !important; }

    /* Mobile create-ticket polish */
    @media (max-width: 640px) {
        .form-input {
            font-size: 16px; /* cegah zoom iOS */
            padding: 12px 14px;
        }
        .cat-option {
            padding: 12px;
            gap: 10px;
            align-items: flex-start;
        }
        .cat-option-icon {
            width: 40px;
            height: 40px;
            margin-top: 1px;
        }
        .damage-option {
            padding: 12px;
            min-height: 48px;
        }
        .time-chip {
            padding: 11px 8px;
            font-size: 12px;
            flex-direction: column;
            gap: 4px;
            text-align: center;
            line-height: 1.25;
        }
        .step-dot {
            width: 24px;
            height: 24px;
            font-size: 10px;
        }
        .create-sticky-actions {
            position: sticky;
            bottom: 0;
            z-index: 30;
            margin-left: -1rem;
            margin-right: -1rem;
            padding: 12px 1rem calc(12px + env(safe-area-inset-bottom, 0px));
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid #F1F5F9;
            box-shadow: 0 -8px 24px rgba(15,23,42,.06);
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto pb-24 sm:pb-10"
     x-data="{
         category: '{{ old('category', '') }}',
         damageCause: '{{ old('damage_cause', '') }}',
         preferredTime: '{{ old('preferred_time', '08:00 - 10:00') }}',
         previewUrls: [],
         handleFiles(e) {
             const sel = Array.from(e.target.files).slice(0,5);
             this.previewUrls = sel.map(f => URL.createObjectURL(f));
         },
         get step() {
             if (!this.category) return 1;
             return 2;
         }
     }">

    {{-- Header --}}
    <div class="band-bg rounded-2xl px-4 sm:px-6 py-5 sm:py-6 mb-4 sm:mb-5 relative overflow-hidden shadow-xl shadow-orange-900/10">
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-orange-500/10 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-sky-400/10 blur-2xl pointer-events-none"></div>

        <div class="relative">
            <div class="flex items-start justify-between gap-3 mb-4 sm:mb-5">
                <div class="min-w-0">
                    <div class="inline-flex items-center gap-1.5 border border-orange-400/30 text-orange-200 text-[11px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1 rounded-full mb-2.5 sm:mb-3 bg-orange-500/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                        Tiket Baru
                    </div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white leading-snug">Ajukan Tiket Servis</h1>
                    <p class="text-slate-300 text-xs sm:text-sm mt-1.5 max-w-sm leading-relaxed">
                        Isi formulir di bawah — teknisi terbaik akan dikirim ke lokasi Anda di Kab. Tangerang.
                    </p>
                    <div class="mt-2.5 sm:mt-3 flex items-center gap-1.5 text-[11px] sm:text-xs text-white/40">
                        <a href="{{ route('customer.dashboard') }}" class="hover:text-orange-300 transition-colors">Dashboard</a>
                        <span>/</span>
                        <span class="text-orange-200/80 font-medium">Ajukan Servis</span>
                    </div>
                </div>
                <div class="hidden sm:flex w-14 h-14 rounded-2xl bg-orange-500/20 border border-orange-400/20 items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            {{-- Progress stepper --}}
            <div class="flex items-center gap-0.5 sm:gap-1" aria-label="Progress pengajuan">
                @foreach(['Kategori','Detail','Alamat','Jadwal','Kirim'] as $i => $s)
                <div class="flex items-center gap-0.5 sm:gap-1 flex-1 min-w-0">
                    <div class="step-dot"
                         :class="{
                             'done': {{ $i + 1 }} < step,
                             'active': {{ $i + 1 }} === step,
                             'idle': {{ $i + 1 }} > step
                         }">{{ $i + 1 }}</div>
                    <span class="hidden md:block text-[10px] font-medium truncate"
                          :class="{{ $i + 1 }} <= step ? 'text-orange-200' : 'text-white/30'">{{ $s }}</span>
                    @if($i < 4)
                    <div class="flex-1 h-0.5 rounded-full mx-0.5 sm:mx-1 min-w-[8px]"
                         :class="{{ $i + 1 }} < step ? 'bg-orange-500' : 'bg-white/10'"></div>
                    @endif
                </div>
                @endforeach
            </div>
            <p class="mt-2 text-[11px] text-orange-200/70 sm:hidden" x-text="['Kategori','Detail','Alamat','Jadwal','Kirim'][step - 1] || 'Kategori'"></p>
        </div>
    </div>

    <form method="POST" action="{{ route('customer.tickets.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- ════════════════════════════════════════
             1. JENIS ELEKTRONIK
        ════════════════════════════════════════ --}}
        <div class="form-section form-section--cat fade-up fade-up-1">
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0"
                     style="background:linear-gradient(135deg,#F97316,#EA580C)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-extrabold font-display text-gray-900">Jenis Elektronik</p>
                    <p class="text-xs text-gray-400 truncate">Pilih salah satu jenis perangkat</p>
                </div>
                <span class="ml-auto text-red-400 font-bold flex-shrink-0">*</span>
            </div>

            <div class="p-4 sm:p-5 space-y-3">
                <div class="space-y-2">
                    @foreach([
                        ['value' => 'HP',     'label' => 'HP / Smartphone', 'sub' => 'iPhone, Samsung, Xiaomi, Oppo, Vivo', 'active' => 'active-hp'],
                        ['value' => 'LAPTOP', 'label' => 'Laptop',          'sub' => 'Notebook, MacBook, Asus, Lenovo',      'active' => 'active-laptop'],
                        ['value' => 'TV',     'label' => 'TV & Monitor',    'sub' => 'Smart TV, LED, monitor kantor',       'active' => 'active-tv'],
                    ] as $cat)
                    @php $catImage = $categoryImages[$cat['value']] ?? null; @endphp
                    <label class="block" @click="category='{{ $cat['value'] }}'">
                        <input type="radio" name="category" value="{{ $cat['value'] }}" class="sr-only"
                               {{ $loop->first ? 'required' : '' }}
                               {{ old('category') === $cat['value'] ? 'checked' : '' }}>
                        <div class="cat-option"
                             :class="category === '{{ $cat['value'] }}' ? '{{ $cat['active'] }}' : ''">
                            <div class="cat-option-icon">
                                @if($catImage)
                                    <img src="{{ $catImage }}" alt="{{ $cat['label'] }}"
                                         loading="lazy"
                                         decoding="async"
                                         onerror="this.classList.add('hidden'); const fb=this.nextElementSibling; if(fb) fb.classList.remove('hidden');">
                                @endif
                                <span class="cat-fallback {{ $catImage ? 'hidden' : '' }}" aria-hidden="true">
                                @if($cat['value'] === 'HP')
                                <svg viewBox="0 0 64 64" width="28" height="28" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="18" y="6" width="28" height="52" rx="5" fill="#FFEDD5" stroke="#FB923C" stroke-width="1.5"/>
                                    <rect x="22" y="12" width="20" height="36" rx="2" fill="#FDBA74" fill-opacity=".35"/>
                                    <circle cx="32" cy="52" r="2.5" fill="#F97316"/>
                                </svg>
                                @elseif($cat['value'] === 'LAPTOP')
                                <svg viewBox="0 0 64 64" width="28" height="28" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="14" width="48" height="30" rx="3" fill="#E0F2FE" stroke="#7DD3FC" stroke-width="1.5"/>
                                    <rect x="12" y="18" width="40" height="22" rx="2" fill="#0EA5E9" fill-opacity=".15"/>
                                    <path d="M4 44 H60 L56 50 H8 Z" fill="#BAE6FD" stroke="#7DD3FC" stroke-width="1.5"/>
                                </svg>
                                @else
                                <svg viewBox="0 0 64 64" width="28" height="28" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="4" y="8" width="56" height="34" rx="4" fill="#EDE9FE" stroke="#C4B5FD" stroke-width="1.5"/>
                                    <rect x="9" y="12" width="46" height="25" rx="2.5" fill="#7C3AED"/>
                                    <path d="M22 42 L19 55 L25 55 L27 42Z" fill="#DDD6FE" stroke="#C4B5FD" stroke-width="1"/>
                                    <path d="M42 42 L37 42 L39 57 L43 57 Z" fill="#DDD6FE" stroke="#C4B5FD" stroke-width="1"/>
                                </svg>
                                @endif
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-tight">{{ $cat['label'] }}</p>
                                <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5 leading-snug">{{ $cat['sub'] }}</p>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all mt-0.5"
                                 :class="category === '{{ $cat['value'] }}' ? 'border-orange-500 bg-orange-500' : 'border-gray-300 bg-white'">
                                <svg x-show="category === '{{ $cat['value'] }}'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('category')
                <p class="mt-3 text-xs text-red-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>
        </div>

        {{-- ════════════════════════════════════════
             2. DETAIL KERUSAKAN
        ════════════════════════════════════════ --}}
        <div class="form-section form-section--detail fade-up fade-up-2">
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#EC4899,#F43F5E)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-extrabold font-display text-gray-900">Detail Kerusakan</p>
                    <p class="text-xs text-gray-400">Semakin detail, teknisi semakin siap</p>
                </div>
            </div>
            <div class="p-4 sm:p-5 space-y-4">
                <div>
                    <label for="brand" class="flex items-center gap-1 text-xs font-semibold text-gray-600 mb-2">
                        Merek / Model
                        <span class="font-normal text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded text-[10px]">opsional</span>
                    </label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="form-input"
                           placeholder="Contoh: Asus Vivobook, iPhone 13, Samsung UA55...">
                    @error('brand') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-1 text-xs font-semibold text-gray-600 mb-2">
                        Penyebab / Jenis Kerusakan <span class="text-red-400 ml-0.5">*</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-3">Pilih salah satu yang paling sesuai</p>

                    @php
                    $damageOptions = [
                        'jatuh'      => 'Rusak karena jatuh',
                        'air'        => 'Kecelup / kena air',
                        'banting'    => 'Kebanting / terbentur',
                        'mati_tiba'  => 'Tiba-tiba mati / tidak nyala',
                        'layar'      => 'Layar rusak / bergaris / blank',
                        'baterai'    => 'Baterai bermasalah / cepat habis',
                        'charge'     => 'Tidak bisa charge / port rusak',
                        'lainnya'    => 'Lainnya',
                    ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($damageOptions as $value => $label)
                        <label class="block" @click="damageCause = '{{ $value }}'">
                            <input type="radio" name="damage_cause" value="{{ $value }}" class="sr-only"
                                   {{ $loop->first ? 'required' : '' }}
                                   {{ old('damage_cause') === $value ? 'checked' : '' }}>
                            <div class="damage-option"
                                 :class="damageCause === '{{ $value }}' ? 'active' : ''">
                                <div class="radio-dot">
                                    <svg x-show="damageCause === '{{ $value }}'" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium"
                                      :class="damageCause === '{{ $value }}' ? 'text-pink-800' : 'text-gray-700'">{{ $label }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('damage_cause')
                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div x-show="damageCause === 'lainnya'" x-cloak x-transition class="space-y-2">
                    <label for="description_other" class="flex items-center gap-1 text-xs font-semibold text-gray-600">
                        Jelaskan masalahnya <span class="text-red-400 ml-0.5">*</span>
                    </label>
                    <textarea id="description_other" name="description_other" rows="3" class="form-input resize-none"
                              placeholder="Ceritakan gejala kerusakannya secara singkat..."
                              :required="damageCause === 'lainnya'">{{ old('description_other') }}</textarea>
                    @error('description_other') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             3. ALAMAT PENJEMPUTAN
        ════════════════════════════════════════ --}}
        <div class="form-section form-section--addr fade-up fade-up-3">
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#F59E0B,#EF4444)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-extrabold font-display text-gray-900">Alamat Penjemputan</p>
                    <p class="text-xs text-gray-400">Teknisi akan datang ke alamat ini</p>
                </div>
                <span class="ml-auto text-red-400 font-bold">*</span>
            </div>
            <div class="p-4 sm:p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Nama Penerima</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2"><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                            <input type="text" value="{{ auth()->user()->name }}" readonly class="form-input pl-9 bg-gray-50 text-gray-500 cursor-not-allowed">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">No. WhatsApp</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                            <input type="text" value="{{ auth()->user()->phone ?? '-' }}" readonly class="form-input pl-9 bg-gray-50 text-gray-500 cursor-not-allowed">
                        </div>
                    </div>
                </div>
                <div>
                    <label for="address" class="text-xs font-semibold text-gray-600 mb-1.5 flex items-center gap-1">
                        Alamat Lengkap <span class="text-red-400">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2" class="form-input resize-none"
                              placeholder="Nama jalan, nomor rumah, RT/RW...">{{ old('address') }}</textarea>
                    @error('address') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="district" class="text-xs font-semibold text-gray-600 mb-1.5 block">Kecamatan / Kelurahan <span class="font-normal text-gray-400">(opsional)</span></label>
                        <input type="text" id="district" name="district" value="{{ old('district') }}" class="form-input" placeholder="Kec. Curug">
                        @error('district') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="text-xs font-semibold text-gray-600 mb-1.5 flex items-center gap-1">Kota / Kabupaten <span class="text-red-400">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="form-input" placeholder="Kabupaten Tangerang">
                        @error('city') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="postal_code" class="text-xs font-semibold text-gray-600 mb-1.5 block">Kode Pos <span class="font-normal text-gray-400">(opsional)</span></label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" class="form-input" maxlength="5" placeholder="65141">
                        @error('postal_code') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address_notes" class="text-xs font-semibold text-gray-600 mb-1.5 block">Patokan / Catatan Lokasi <span class="font-normal text-gray-400">(opsional)</span></label>
                        <input type="text" id="address_notes" name="address_notes" value="{{ old('address_notes') }}" class="form-input" placeholder="Dekat Alfamart, pintu gerbang warna biru...">
                        @error('address_notes') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex items-start gap-2.5 bg-amber-50 border border-amber-100 rounded-xl px-3.5 py-3">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-amber-700">Pastikan alamat dapat diakses teknisi. Jika kesulitan menemukan, teknisi akan menghubungi nomor WhatsApp Anda.</p>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             4. JADWAL
        ════════════════════════════════════════ --}}
        <div class="form-section form-section--sched fade-up fade-up-4">
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#F59E0B,#D97706)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-extrabold font-display text-gray-900">Jadwal Kunjungan</p>
                    <p class="text-xs text-gray-400">Pilih tanggal & jam yang paling cocok</p>
                </div>
            </div>
            <div class="p-4 sm:p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="preferred_date" class="text-xs font-semibold text-gray-600 mb-1.5 block">Tanggal Pilihan <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2"><svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                            <input type="date" id="preferred_date" name="preferred_date"
                                   value="{{ old('preferred_date', now()->addDay()->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="form-input pl-9">
                        </div>
                        @error('preferred_date') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-2 block">Jam Pilihan <span class="text-red-400">*</span></label>
                        <input type="hidden" name="preferred_time" :value="preferredTime">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['08:00 - 10:00','10:00 - 12:00','13:00 - 15:00','15:00 - 17:00'] as $t)
                            <div class="time-chip"
                                 :class="preferredTime === '{{ $t }}' ? 'active' : ''"
                                 @click="preferredTime = '{{ $t }}'">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $t }}</span>
                                <span class="text-[10px] opacity-70 sm:hidden">WIB</span>
                                <span class="hidden sm:inline"> WIB</span>
                            </div>
                            @endforeach
                        </div>
                        @error('preferred_time') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span class="flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-medium px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Senin – Jumat</span>
                    <span class="flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-100 text-xs font-medium px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Sabtu tersedia</span>
                    <span class="flex items-center gap-1.5 bg-red-50 text-red-500 border border-red-100 text-xs font-medium px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Minggu & Hari libur — tutup</span>
                </div>
            </div>
        </div>

        {{-- 5. FOTO KERUSAKAN (WAJIB) --}}
        <div class="form-section form-section--photo fade-up fade-up-5">
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#8B5CF6,#7C3AED)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-extrabold font-display text-gray-900">Foto Kerusakan</p>
                    <p class="text-xs text-gray-400">Wajib upload minimal 1 foto · maks 5 foto</p>
                </div>
                <span class="ml-auto text-red-400 font-bold">*</span>
            </div>
            <div class="p-4 sm:p-5">
                <label class="block cursor-pointer group">
                    <input type="file" name="photos[]" accept="image/*" multiple required class="sr-only" @change="handleFiles($event)">
                    <div class="rounded-xl border-2 border-dashed transition-all duration-200
                                @if($errors->has('photos') || $errors->has('photos.*')) border-red-300 bg-red-50/30
                                @else border-gray-200 hover:border-violet-300 hover:bg-violet-50/20 @endif"
                         :class="previewUrls.length ? 'border-violet-300 bg-violet-50/30' : ''">
                        <template x-if="previewUrls.length > 0">
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(url,idx) in previewUrls" :key="idx">
                                        <div class="w-14 h-14 rounded-lg overflow-hidden border-2 border-white shadow-md">
                                            <img :src="url" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs font-semibold text-violet-600" x-text="previewUrls.length + ' foto dipilih — klik untuk mengubah'"></p>
                            </div>
                        </template>
                        <template x-if="previewUrls.length === 0">
                            <div class="py-7 flex flex-col items-center gap-2.5 text-center">
                                <div class="w-12 h-12 rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Klik untuk upload foto kerusakan</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Wajib minimal 1 foto · JPG, PNG, WEBP — maks 2MB</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </label>
                @error('photos')
                <p class="mt-2 text-xs text-red-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
                @error('photos.*')
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Info alur & Submit --}}
        <div class="flex gap-3 rounded-2xl overflow-hidden border border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 fade-up">
            <div class="w-1 flex-shrink-0 bg-gradient-to-b from-orange-400 to-amber-500"></div>
            <div class="py-4 pr-4">
                <p class="text-xs font-bold text-orange-900 mb-2">Yang terjadi setelah pengajuan:</p>
                <div class="space-y-1.5">
                    @foreach(['Notifikasi WhatsApp dikirim ke nomor Anda','Admin verifikasi & pilih teknisi terbaik','Teknisi datang sesuai jadwal','Notif WhatsApp lagi saat servis selesai ✓'] as $i => $s)
                    <div class="flex items-center gap-2 text-xs text-orange-800/80">
                        <span class="w-5 h-5 rounded-full bg-orange-200 text-orange-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">{{ $i+1 }}</span>
                        {{ $s }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="create-sticky-actions sm:!static sm:!m-0 sm:!p-0 sm:!bg-transparent sm:!shadow-none sm:!border-0 sm:!backdrop-none flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 pt-1">
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center justify-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-700 bg-white border border-gray-200 hover:border-orange-200 hover:bg-orange-50 px-4 py-3 rounded-xl transition-all shadow-sm sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Batal
            </a>
            <button type="submit"
                    class="w-full sm:flex-1 flex items-center justify-center gap-2 text-white font-bold py-3.5 rounded-xl transition-all text-sm shadow-lg shadow-orange-300/50 hover:shadow-orange-400/60 hover:-translate-y-0.5 active:translate-y-0 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Pengajuan Servis
            </button>
        </div>
    </form>
</div>
@endsection
