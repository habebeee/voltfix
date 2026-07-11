@extends('layouts.app')
@section('title', 'Ajukan Servis — Voltfix')

@push('head')
<style>
    .form-input {
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
        border-color: #3B82F6;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }
    .form-input::placeholder { color: #D1D5DB; }

    /* ── Professional navy header (tidak alay) ── */
    .page-header {
        background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 55%, #1E40AF 100%);
    }

    /* ── Category cards — fixed equal height ── */
    .cat-card {
        min-height: 210px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px 12px 16px;
        border-radius: 16px;
        border: 2px solid #E2E8F0;
        background: #F8FAFC;
        cursor: pointer;
        transition: all .2s ease;
        position: relative;
        user-select: none;
    }
    .cat-card:hover { border-color: #94A3B8; box-shadow: 0 4px 12px rgba(0,0,0,.08); transform: translateY(-2px); }

    @keyframes fadeUp {
        from { opacity:0; transform: translateY(8px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .fade-up   { animation: fadeUp .3s ease both; }
    .fade-up-1 { animation-delay:.04s; }
    .fade-up-2 { animation-delay:.10s; }
    .fade-up-3 { animation-delay:.16s; }
    .fade-up-4 { animation-delay:.22s; }
    .fade-up-5 { animation-delay:.28s; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto pb-8"
     x-data="{
         category: '{{ old('category', '') }}',
         previewUrls: [],
         handleFiles(e) {
             const sel = Array.from(e.target.files).slice(0,5);
             this.previewUrls = sel.map(f => URL.createObjectURL(f));
         }
     }">

    {{-- ── Page header (professional deep navy) ── --}}
    <div class="page-header rounded-2xl px-6 py-5 mb-6 relative overflow-hidden shadow-lg">
        {{-- Subtle decorative circles --}}
        <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full" style="background:rgba(255,255,255,.04)"></div>
        <div class="absolute -bottom-8 -left-8 w-36 h-36 rounded-full"  style="background:rgba(255,255,255,.03)"></div>

        <div class="relative flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 border border-white/20 text-white/70 text-xs font-medium px-3 py-1 rounded-full mb-3"
                     style="background:rgba(255,255,255,.1)">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tiket Baru
                </div>
                <h1 class="text-xl font-bold text-white leading-snug">Ajukan Tiket Servis</h1>
                <p class="text-slate-300 text-sm mt-1 max-w-xs leading-relaxed">
                    Isi formulir dan kami segera mengirim teknisi terbaik ke lokasi Anda.
                </p>
                <div class="mt-3 flex items-center gap-1.5 text-xs text-white/40">
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-white/80 transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-white/70 font-medium">Ajukan Servis</span>
                </div>
            </div>

            {{-- Step list --}}
            <div class="hidden sm:flex flex-col gap-2 text-xs flex-shrink-0 mt-1">
                @foreach(['Pilih Kategori','Detail Kerusakan','Alamat','Jadwal & Foto','Kirim'] as $i => $s)
                <div class="flex items-center gap-2 text-white/50">
                    <div class="w-4.5 h-4.5 rounded-full text-[10px] font-bold text-white flex items-center justify-center flex-shrink-0"
                         style="width:18px;height:18px;background:rgba(255,255,255,.18)">{{ $i+1 }}</div>
                    <span>{{ $s }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('customer.tickets.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- ════════════════════════════════════════
             1. JENIS ELEKTRONIK
        ════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up fade-up-1">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm"
                     style="background:linear-gradient(135deg,#2563EB,#1D4ED8)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Jenis Elektronik</p>
                    <p class="text-xs text-gray-400">Pilih perangkat yang perlu diservis</p>
                </div>
                <span class="ml-auto text-red-400 font-bold">*</span>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-3 gap-3">

                    {{-- ─ KULKAS ─ --}}
                    <label @click="category='KULKAS'">
                        <input type="radio" name="category" value="KULKAS" class="sr-only"
                               {{ old('category')==='KULKAS' ? 'checked' : '' }}>
                        <div class="cat-card"
                             :style="category==='KULKAS'
                                 ? 'border-color:#3B82F6;background:#EFF6FF;box-shadow:0 8px 20px -4px rgba(59,130,246,.2);transform:translateY(-4px)'
                                 : ''">
                            {{-- SVG Kulkas 64×64 --}}
                            <div class="transition-transform duration-200" :class="category==='KULKAS'?'scale-110':'scale-100'">
                                <svg viewBox="0 0 64 64" width="76" height="76" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="kb" x1="0" y1="0" x2="1" y2="0"><stop stop-color="#DBEAFE"/><stop offset="1" stop-color="#BAE6FD"/></linearGradient>
                                        <linearGradient id="kf" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#93C5FD"/><stop offset="1" stop-color="#DBEAFE" stop-opacity="0"/></linearGradient>
                                    </defs>
                                    <rect x="14" y="2" width="36" height="60" rx="5" fill="url(#kb)" stroke="#93C5FD" stroke-width="1.5"/>
                                    <rect x="14" y="2"  width="36" height="22" rx="5" fill="url(#kf)"/>
                                    <line x1="14" y1="24" x2="50" y2="24" stroke="#93C5FD" stroke-width="1.5"/>
                                    <rect x="39" y="8"  width="4" height="10" rx="2" fill="white" fill-opacity=".75" stroke="#93C5FD" stroke-width="1"/>
                                    <rect x="39" y="30" width="4" height="22" rx="2" fill="white" fill-opacity=".75" stroke="#93C5FD" stroke-width="1"/>
                                    <rect x="18" y="6"  width="7" height="13" rx="2" fill="white" fill-opacity=".18"/>
                                    <rect x="18" y="28" width="7" height="22" rx="2" fill="white" fill-opacity=".12"/>
                                    <circle cx="24" cy="4.5" r="1.5" fill="#60A5FA"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold" :class="category==='KULKAS'?'text-blue-700':'text-gray-700'">Kulkas</p>
                                <p class="text-[11px] mt-0.5" :class="category==='KULKAS'?'text-blue-400':'text-gray-400'">Kulkas & Freezer</p>
                            </div>
                            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-md transition-all duration-200"
                                 style="background:#2563EB"
                                 :class="category==='KULKAS'?'opacity-100 scale-100':'opacity-0 scale-0'">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </label>

                    {{-- ─ TV ─ --}}
                    <label @click="category='TV'">
                        <input type="radio" name="category" value="TV" class="sr-only"
                               {{ old('category')==='TV' ? 'checked' : '' }}>
                        <div class="cat-card"
                             :style="category==='TV'
                                 ? 'border-color:#7C3AED;background:#F5F3FF;box-shadow:0 8px 20px -4px rgba(124,58,237,.2);transform:translateY(-4px)'
                                 : ''">
                            {{-- SVG TV 64×64 --}}
                            <div class="transition-transform duration-200" :class="category==='TV'?'scale-110':'scale-100'">
                                <svg viewBox="0 0 64 64" width="76" height="76" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="tb" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#EDE9FE"/><stop offset="1" stop-color="#DDD6FE"/></linearGradient>
                                        <linearGradient id="ts" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#6D28D9"/><stop offset="1" stop-color="#4C1D95"/></linearGradient>
                                        <linearGradient id="tl" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#DDD6FE"/><stop offset="1" stop-color="#C4B5FD"/></linearGradient>
                                    </defs>
                                    <rect x="3" y="7" width="58" height="36" rx="4" fill="url(#tb)" stroke="#C4B5FD" stroke-width="1.5"/>
                                    <rect x="8" y="11" width="48" height="27" rx="2.5" fill="url(#ts)"/>
                                    <path d="M11 14 Q17 11 23 13 L21 21 Q14 18 11 20Z" fill="white" fill-opacity=".12"/>
                                    <circle cx="56" cy="9.5" r="1.5" fill="#34D399"/>
                                    <circle cx="32" cy="9.5" r="1.2" fill="#A78BFA" fill-opacity=".5"/>
                                    <path d="M22 43 L19 56 Q19 58 21 58 L25 58 L27 43Z"       fill="url(#tl)" stroke="#C4B5FD" stroke-width="1"/>
                                    <path d="M42 43 L37 43 L39 58 L43 58 Q45 58 45 56Z"       fill="url(#tl)" stroke="#C4B5FD" stroke-width="1"/>
                                    <rect x="4.5"  y="18" width="2" height="14" rx="1" fill="#A78BFA" fill-opacity=".3"/>
                                    <rect x="57.5" y="18" width="2" height="14" rx="1" fill="#A78BFA" fill-opacity=".3"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold" :class="category==='TV'?'text-violet-700':'text-gray-700'">TV</p>
                                <p class="text-[11px] mt-0.5" :class="category==='TV'?'text-violet-400':'text-gray-400'">Smart TV & Monitor</p>
                            </div>
                            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-md transition-all duration-200"
                                 style="background:#7C3AED"
                                 :class="category==='TV'?'opacity-100 scale-100':'opacity-0 scale-0'">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </label>

                    {{-- ─ MESIN CUCI ─ --}}
                    <label @click="category='MESIN_CUCI'">
                        <input type="radio" name="category" value="MESIN_CUCI" class="sr-only"
                               {{ old('category')==='MESIN_CUCI' ? 'checked' : '' }}>
                        <div class="cat-card"
                             :style="category==='MESIN_CUCI'
                                 ? 'border-color:#059669;background:#ECFDF5;box-shadow:0 8px 20px -4px rgba(5,150,105,.2);transform:translateY(-4px)'
                                 : ''">
                            {{-- SVG Mesin Cuci 64×64 --}}
                            <div class="transition-transform duration-200" :class="category==='MESIN_CUCI'?'scale-110':'scale-100'">
                                <svg viewBox="0 0 64 64" width="76" height="76" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="wb" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#D1FAE5"/><stop offset="1" stop-color="#CCFBF1"/></linearGradient>
                                        <linearGradient id="wp" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#6EE7B7"/><stop offset="1" stop-color="#D1FAE5" stop-opacity="0"/></linearGradient>
                                        <linearGradient id="wd" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#059669"/><stop offset="1" stop-color="#0D9488"/></linearGradient>
                                        <linearGradient id="wg" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#065F46" stop-opacity=".85"/><stop offset="1" stop-color="#0D9488" stop-opacity=".9"/></linearGradient>
                                    </defs>
                                    <rect x="5" y="4" width="54" height="56" rx="5" fill="url(#wb)" stroke="#6EE7B7" stroke-width="1.5"/>
                                    <rect x="5" y="4" width="54" height="13" rx="5" fill="url(#wp)"/>
                                    <line x1="5" y1="17" x2="59" y2="17" stroke="#6EE7B7" stroke-width="1.3"/>
                                    <circle cx="13" cy="10.5" r="3"   fill="#10B981" stroke="white" stroke-width="1.5"/>
                                    <circle cx="22" cy="10.5" r="3"   fill="#34D399" stroke="white" stroke-width="1.5"/>
                                    <rect x="32" y="6" width="23" height="9" rx="2.5" fill="#064E3B" fill-opacity=".6"/>
                                    <text x="43.5" y="12.5" text-anchor="middle" font-size="5" fill="#6EE7B7" font-family="monospace" font-weight="bold">40°C</text>
                                    <circle cx="32" cy="40" r="17" fill="url(#wd)" stroke="#A7F3D0" stroke-width="1.5"/>
                                    <circle cx="32" cy="40" r="13" fill="url(#wg)"/>
                                    <line x1="32" y1="27" x2="32" y2="33" stroke="#A7F3D0" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="32" y1="47" x2="32" y2="53" stroke="#A7F3D0" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="19" y1="40" x2="25" y2="40" stroke="#A7F3D0" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="39" y1="40" x2="45" y2="40" stroke="#A7F3D0" stroke-width="2" stroke-linecap="round"/>
                                    <circle cx="32" cy="40" r="3.5" fill="#6EE7B7" fill-opacity=".5" stroke="#34D399" stroke-width="1.2"/>
                                    <path d="M21 33 Q26 29 30 31 L28 37 Q23 34 21 36Z" fill="white" fill-opacity=".18"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold" :class="category==='MESIN_CUCI'?'text-emerald-700':'text-gray-700'">Mesin Cuci</p>
                                <p class="text-[11px] mt-0.5" :class="category==='MESIN_CUCI'?'text-emerald-500':'text-gray-400'">Top & Front Loading</p>
                            </div>
                            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-md transition-all duration-200"
                                 style="background:#059669"
                                 :class="category==='MESIN_CUCI'?'opacity-100 scale-100':'opacity-0 scale-0'">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </label>

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
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up fade-up-2">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#EC4899,#F43F5E)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Detail Kerusakan</p>
                    <p class="text-xs text-gray-400">Semakin detail, teknisi semakin siap</p>
                </div>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label for="brand" class="flex items-center gap-1 text-xs font-semibold text-gray-600 mb-2">
                        Merek / Model
                        <span class="font-normal text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded text-[10px]">opsional</span>
                    </label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="form-input"
                           placeholder="Contoh: Samsung RT20, Sharp SJ-25, LG F1296TD4...">
                    @error('brand') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="flex items-center gap-1 text-xs font-semibold text-gray-600 mb-2">
                        Keluhan / Deskripsi Kerusakan <span class="text-red-400 ml-0.5">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4" class="form-input resize-none"
                              placeholder="Ceritakan gejala kerusakannya... Contoh: Kulkas tidak dingin sejak 2 hari lalu, lampu menyala tapi kompresor tidak bunyi.">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             3. ALAMAT PENJEMPUTAN
        ════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up fade-up-3">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#F59E0B,#EF4444)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Alamat Penjemputan</p>
                    <p class="text-xs text-gray-400">Teknisi akan datang ke alamat ini</p>
                </div>
                <span class="ml-auto text-red-400 font-bold">*</span>
            </div>
            <div class="p-5 space-y-4">
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
                        <input type="text" id="district" name="district" value="{{ old('district') }}" class="form-input" placeholder="Kec. Lowokwaru">
                        @error('district') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="text-xs font-semibold text-gray-600 mb-1.5 flex items-center gap-1">Kota / Kabupaten <span class="text-red-400">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="form-input" placeholder="Kota Malang">
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
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up fade-up-4">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#F59E0B,#D97706)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Jadwal Kunjungan</p>
                    <p class="text-xs text-gray-400">Pilih tanggal & jam yang paling cocok</p>
                </div>
            </div>
            <div class="p-5 space-y-4">
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
                        <label for="preferred_time" class="text-xs font-semibold text-gray-600 mb-1.5 block">Jam Pilihan <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2"><svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                            <select id="preferred_time" name="preferred_time" class="form-input pl-9 pr-8 appearance-none">
                                @foreach(['08:00 - 10:00','10:00 - 12:00','13:00 - 15:00','15:00 - 17:00'] as $t)
                                <option value="{{ $t }}" {{ old('preferred_time')===$t?'selected':'' }}>🕐 {{ $t }} WIB</option>
                                @endforeach
                            </select>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg></span>
                        </div>
                        @error('preferred_time') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span class="flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-medium px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Senin – Jumat</span>
                    <span class="flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-100 text-xs font-medium px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Sabtu tersedia</span>
                    <span class="flex items-center gap-1.5 bg-red-50 text-red-500 border border-red-100 text-xs font-medium px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Minggu & libur — tutup</span>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             5. FOTO (OPSIONAL)
        ════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-up fade-up-5">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-50">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#8B5CF6,#7C3AED)">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Foto Kerusakan
                        <span class="text-xs font-normal text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded ml-1">opsional · maks 5</span>
                    </p>
                    <p class="text-xs text-gray-400">Foto membantu teknisi siapkan spare part</p>
                </div>
            </div>
            <div class="p-5">
                <label class="block cursor-pointer group">
                    <input type="file" name="photos[]" accept="image/*" multiple class="sr-only" @change="handleFiles($event)">
                    <div class="rounded-xl border-2 border-dashed transition-all duration-200"
                         :class="previewUrls.length ? 'border-violet-300 bg-violet-50/30' : 'border-gray-200 hover:border-violet-300 hover:bg-violet-50/20'">
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
                                    <p class="text-sm font-medium text-gray-600">Klik atau seret foto ke sini</p>
                                    <p class="text-xs text-gray-400 mt-0.5">JPG, PNG, WEBP — maks 2MB per foto</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </label>
            </div>
        </div>

        {{-- ── Info alur & Submit ── --}}
        <div class="flex gap-3 rounded-2xl overflow-hidden border border-blue-100 bg-blue-50">
            <div class="w-1 flex-shrink-0 bg-blue-400"></div>
            <div class="py-3.5 pr-4">
                <p class="text-xs font-bold text-blue-800 mb-1.5">Yang terjadi setelah pengajuan:</p>
                <div class="space-y-1">
                    @foreach(['Notifikasi WhatsApp dikirim ke nomor Anda','Admin verifikasi & pilih teknisi terbaik','Teknisi datang sesuai jadwal','Notif WhatsApp lagi saat servis selesai ✓'] as $i => $s)
                    <div class="flex items-center gap-2 text-xs text-blue-700">
                        <span class="w-4 h-4 rounded-full bg-blue-200 text-blue-700 font-bold text-[9px] flex items-center justify-center flex-shrink-0">{{ $i+1 }}</span>
                        {{ $s }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-700 bg-white border border-gray-200 hover:border-gray-300 px-4 py-3 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Batal
            </a>
            <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 text-white font-bold py-3 rounded-xl transition-all text-sm shadow-lg"
                    style="background:linear-gradient(135deg,#1D4ED8,#1E40AF); box-shadow:0 8px 20px -4px rgba(29,78,216,.35);"
                    onmouseover="this.style.background='linear-gradient(135deg,#1E40AF,#1D4ED8)'"
                    onmouseout="this.style.background='linear-gradient(135deg,#1D4ED8,#1E40AF)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Pengajuan Servis
            </button>
        </div>
    </form>
</div>
@endsection
