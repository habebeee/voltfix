@use('Illuminate\Support\Facades\Storage')
@extends('layouts.app')
@section('title', 'Tiket ' . $ticket->invoice_number . ' — Voltfix')

@push('head')
<style>
    .info-row dt { font-size:11px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px; }
    .info-row dd { font-size:14px; color:#111827; font-weight:500; }
</style>
@endpush

@section('content')
@php
$statusCfg = [
    'PENDING'            => ['label'=>'Menunggu Konfirmasi', 'dot'=>'bg-amber-400',   'pill'=>'bg-amber-50 text-amber-700 border-amber-200',    'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    'REJECTED'           => ['label'=>'Ditolak',             'dot'=>'bg-red-400',     'pill'=>'bg-red-50 text-red-700 border-red-200',            'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'WAITING_ASSIGNMENT' => ['label'=>'Mencari Teknisi',     'dot'=>'bg-blue-400',    'pill'=>'bg-blue-50 text-blue-700 border-blue-200',         'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0'],
    'ASSIGNED'           => ['label'=>'Teknisi Ditugaskan',  'dot'=>'bg-indigo-400',  'pill'=>'bg-indigo-50 text-indigo-700 border-indigo-200',   'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    'ON_THE_WAY'         => ['label'=>'Teknisi Dalam Perjalanan','dot'=>'bg-violet-400','pill'=>'bg-violet-50 text-violet-700 border-violet-200', 'icon'=>'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
    'DIAGNOSIS'          => ['label'=>'Diagnosa',            'dot'=>'bg-cyan-400',    'pill'=>'bg-cyan-50 text-cyan-700 border-cyan-200',         'icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
    'WAITING_PART'       => ['label'=>'Menunggu Spare Part', 'dot'=>'bg-orange-400',  'pill'=>'bg-orange-50 text-orange-700 border-orange-200',   'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    'REPAIR'             => ['label'=>'Diperbaiki',          'dot'=>'bg-purple-400',  'pill'=>'bg-purple-50 text-purple-700 border-purple-200',   'icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
    'COMPLETED'          => ['label'=>'Selesai',             'dot'=>'bg-emerald-400', 'pill'=>'bg-emerald-50 text-emerald-700 border-emerald-200','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'CLOSED'             => ['label'=>'Ditutup',             'dot'=>'bg-gray-300',    'pill'=>'bg-gray-100 text-gray-500 border-gray-200',        'icon'=>'M5 13l4 4L19 7'],
];
$s = $statusCfg[$ticket->status] ?? ['label'=>$ticket->status,'dot'=>'bg-gray-300','pill'=>'bg-gray-100 text-gray-600 border-gray-200','icon'=>''];

$catLabel = ['TV'=>'TV / Monitor','HP'=>'HP','LAPTOP'=>'Laptop'][$ticket->category] ?? $ticket->category;
$catColor = ['TV'=>['bg'=>'#F5F3FF','border'=>'#DDD6FE','text'=>'#6D28D9'], 'HP'=>['bg'=>'#FFFBEB','border'=>'#FDE68A','text'=>'#B45309'], 'LAPTOP'=>['bg'=>'#F0F9FF','border'=>'#BAE6FD','text'=>'#0369A1']][$ticket->category] ?? ['bg'=>'#F9FAFB','border'=>'#E5E7EB','text'=>'#374151'];
@endphp

<div class="max-w-3xl mx-auto space-y-5">

    {{-- ── Header card ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Top color strip based on status --}}
        <div class="h-1.5 w-full" style="background:{{ ['PENDING'=>'#F59E0B','REJECTED'=>'#EF4444','WAITING_ASSIGNMENT'=>'#3B82F6','ASSIGNED'=>'#6366F1','ON_THE_WAY'=>'#8B5CF6','DIAGNOSIS'=>'#06B6D4','WAITING_PART'=>'#F97316','REPAIR'=>'#9333EA','COMPLETED'=>'#10B981','CLOSED'=>'#9CA3AF'][$ticket->status] ?? '#9CA3AF' }}"></div>

        <div class="p-6">
            <div class="flex items-start gap-4">
                {{-- Back arrow --}}
                <a href="{{ route('customer.tickets.index') }}"
                   class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100 flex items-center justify-center transition-colors mt-0.5">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div class="flex-1 min-w-0">
                    {{-- Invoice + status --}}
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h1 class="text-xl font-black text-gray-900 tracking-tight">{{ $ticket->invoice_number }}</h1>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold border px-2.5 py-1 rounded-full {{ $s['pill'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                            {{ $s['label'] }}
                        </span>
                    </div>

                    {{-- Category + date meta --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400">
                        <span class="font-medium px-2 py-0.5 rounded-md border text-[11px]"
                              style="background:{{ $catColor['bg'] }};border-color:{{ $catColor['border'] }};color:{{ $catColor['text'] }}">
                            {{ $catLabel }}{{ $ticket->brand ? ' — ' . $ticket->brand : '' }}
                        </span>
                        <span>·</span>
                        <span>Diajukan {{ $ticket->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                {{-- Rating badge if ratable --}}
                @if($ticket->status === 'COMPLETED' && !$ticket->rating)
                <a href="#rating-form"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg transition-colors shadow-sm shadow-amber-200">
                    ⭐ Beri Rating
                </a>
                @endif
            </div>

            {{-- Rejection reason --}}
            @if($ticket->rejection_reason)
            <div class="mt-4 flex items-start gap-3 bg-red-50 border border-red-100 rounded-xl p-3.5">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-xs font-bold text-red-700">Alasan Penolakan</p>
                    <p class="text-sm text-red-600 mt-0.5">{{ $ticket->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Peringatan keras setelah teknisi ditugaskan --}}
    @if($ticket->technician && ! in_array($ticket->status, ['PENDING', 'REJECTED', 'WAITING_ASSIGNMENT', 'CLOSED'], true))
    <div class="rounded-2xl border-2 border-red-500 bg-red-50 shadow-sm overflow-hidden" role="alert">
        <div class="flex gap-3 p-4 sm:p-5">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center shadow-md shadow-red-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-extrabold uppercase tracking-wide text-red-700 mb-1">Peringatan Keras</p>
                <p class="text-sm sm:text-[15px] font-bold text-red-800 leading-snug">
                    * Dilarang keras menitipkan barang / transfer uang ke teknisi tanpa izin ke admin.
                </p>
                <p class="text-xs text-red-600/90 mt-1.5 leading-relaxed">
                    Segala transaksi &amp; penitipan hanya melalui saluran resmi VoltFix. Jika diminta transfer pribadi, segera hubungi admin.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Main grid ────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left col (2/3) --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Detail tiket --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#2563EB,#1D4ED8)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Informasi Tiket</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4 info-row">
                        <div><dt>Jenis Elektronik</dt><dd>{{ $catLabel }}{{ $ticket->brand ? ' — ' . $ticket->brand : '' }}</dd></div>
                        <div><dt>Jadwal Kunjungan</dt><dd>{{ $ticket->preferred_date->format('d M Y') }} · {{ $ticket->preferred_time }}</dd></div>
                        <div class="col-span-2">
                            <dt>Deskripsi Keluhan</dt>
                            <dd class="bg-gray-50 border border-gray-100 rounded-xl p-3 mt-1.5 text-sm leading-relaxed font-normal">{{ $ticket->description }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alamat penjemputan --}}
            @if($ticket->address)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#F59E0B,#EF4444)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Alamat Penjemputan</h2>
                </div>
                <div class="p-5">
                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $ticket->address }}</p>
                                @if($ticket->district)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->district }}</p>
                                @endif
                                <p class="text-xs text-gray-600 mt-0.5 font-medium">
                                    {{ $ticket->city }}{{ $ticket->postal_code ? ', ' . $ticket->postal_code : '' }}
                                </p>
                                @if($ticket->address_notes)
                                <div class="mt-2 flex items-center gap-1.5 text-xs text-orange-700 font-medium">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    Patokan: {{ $ticket->address_notes }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Foto --}}
            @if($ticket->photo_urls && count($ticket->photo_urls) > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#8B5CF6,#7C3AED)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Foto Kerusakan</h2>
                    <span class="ml-auto text-xs text-gray-400">{{ count($ticket->photo_urls) }} foto</span>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5">
                        @foreach($ticket->photo_urls as $url)
                        <a href="{{ Storage::url($url) }}" target="_blank" class="group relative block">
                            <img src="{{ Storage::url($url) }}"
                                 class="w-full aspect-square object-cover rounded-xl border border-gray-100 group-hover:opacity-85 transition-opacity shadow-sm"
                                 alt="Foto kerusakan">
                            <div class="absolute inset-0 rounded-xl bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Rating form --}}
            @if($ticket->status === 'COMPLETED' && !$ticket->rating)
            <div id="rating-form" class="bg-white rounded-2xl border-2 border-amber-200 shadow-sm overflow-hidden"
                 x-data="{ rating: 0, hovered: 0 }">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-amber-100"
                     style="background:linear-gradient(135deg,#FFFBEB,#FEF3C7)">
                    <div class="w-6 h-6 rounded-md bg-amber-400 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-amber-900">Beri Rating Teknisi</h2>
                        <p class="text-xs text-amber-600 mt-0.5">Bantu kami meningkatkan kualitas layanan</p>
                    </div>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('customer.tickets.rate', $ticket) }}">
                        @csrf
                        <input type="hidden" name="rating" :value="rating">

                        {{-- Stars --}}
                        <div class="flex items-center gap-2 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    @click="rating = {{ $i }}"
                                    @mouseenter="hovered = {{ $i }}"
                                    @mouseleave="hovered = 0"
                                    class="text-4xl leading-none transition-all duration-100 hover:scale-110 focus:outline-none">
                                <span :class="(hovered || rating) >= {{ $i }} ? 'text-amber-400' : 'text-gray-200'">★</span>
                            </button>
                            @endfor
                            <span class="ml-1 text-sm font-semibold text-amber-700"
                                  x-show="rating > 0"
                                  x-text="['','Sangat Buruk','Buruk','Cukup','Bagus','Sangat Bagus'][rating]"></span>
                        </div>

                        <textarea name="review" rows="3"
                                  class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 resize-none bg-amber-50/30 mb-4"
                                  placeholder="Ceritakan pengalaman Anda dengan teknisi kami... (opsional)"></textarea>

                        <button type="submit"
                                x-bind:disabled="rating === 0"
                                class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold py-2.5 rounded-xl transition-colors text-sm shadow-md shadow-amber-200">
                            Kirim Rating & Ulasan
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Rating sudah diberikan --}}
            @if($ticket->rating)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md bg-amber-400 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Rating Anda</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-1.5 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        <span class="text-2xl {{ $i <= $ticket->rating->rating ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                        @endfor
                        <span class="ml-1 text-sm font-bold text-gray-700">{{ $ticket->rating->rating }}/5</span>
                    </div>
                    @if($ticket->rating->review)
                    <p class="text-sm text-gray-600 italic bg-gray-50 rounded-xl p-3 border border-gray-100">"{{ $ticket->rating->review }}"</p>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- /left col --}}

        {{-- Right col (1/3) --}}
        <div class="space-y-5">

            {{-- Teknisi --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#10B981,#059669)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Teknisi</h2>
                </div>
                @if($ticket->technician)
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-sm flex-shrink-0">
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($ticket->technician->user->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $ticket->technician->user->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ ['TV'=>'Spesialis TV & Monitor','HP'=>'Spesialis HP','LAPTOP'=>'Spesialis Laptop'][$ticket->technician->skill_category] ?? $ticket->technician->skill_category }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-2.5 info-row">
                        <div class="flex justify-between items-center">
                            <dt class="mb-0">Rating</dt>
                            <dd class="flex items-center gap-1">
                                <span class="text-amber-400 text-sm">★</span>
                                <span class="text-sm font-bold text-gray-800">{{ number_format($ticket->technician->average_rating, 1) }}</span>
                            </dd>
                        </div>
                        @if($ticket->technician->experience)
                        <div class="flex justify-between items-center">
                            <dt class="mb-0">Pengalaman</dt>
                            <dd class="text-sm">{{ $ticket->technician->experience }}</dd>
                        </div>
                        @endif
                    </div>
                    @if($ticket->technician->user->phone)
                    <a href="https://wa.me/{{ $ticket->technician->user->phone }}" target="_blank"
                       class="mt-4 flex items-center justify-center gap-2 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-2 rounded-xl transition-colors w-full">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                    @endif
                    @if(! in_array($ticket->status, ['COMPLETED', 'CLOSED', 'REJECTED'], true))
                    <p class="mt-3 text-[11px] leading-snug text-red-700 font-semibold bg-red-50 border border-red-200 rounded-lg px-2.5 py-2">
                        * Dilarang keras menitipkan barang/transfer uang ke teknisi tanpa izin ke admin.
                    </p>
                    @endif
                </div>
                @else
                <div class="p-5 text-center text-sm text-gray-400 py-8">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p class="text-xs">Teknisi belum ditugaskan</p>
                </div>
                @endif
            </div>

            {{-- Timeline status --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#6366F1,#4F46E5)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Riwayat Status</h2>
                </div>
                <div class="p-5">
                    <div class="relative pl-5">
                        {{-- Vertical line --}}
                        <div class="absolute left-1.5 top-2 bottom-2 w-px bg-gray-100"></div>

                        @foreach($ticket->logs->sortByDesc('created_at') as $i => $log)
                        @php
                        $dotColor = match($log->new_status) {
                            'COMPLETED' => 'bg-emerald-400',
                            'REJECTED'  => 'bg-red-400',
                            'PENDING'   => 'bg-amber-400',
                            default     => 'bg-indigo-400',
                        };
                        @endphp
                        <div class="relative mb-4 last:mb-0">
                            {{-- Dot --}}
                            <div class="absolute -left-5 top-1 w-3 h-3 rounded-full {{ $dotColor }} border-2 border-white shadow-sm"></div>

                            <div class="bg-gray-50/80 rounded-xl px-3.5 py-2.5 border border-gray-100">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <p class="text-xs font-bold text-gray-800">
                                        {{ $statusCfg[$log->new_status]['label'] ?? $log->new_status }}
                                    </p>
                                    @if($i === 0)
                                    <span class="text-[10px] bg-indigo-100 text-indigo-600 font-semibold px-1.5 py-0.5 rounded-full">Terbaru</span>
                                    @endif
                                </div>
                                @if($log->note)
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $log->note }}</p>
                                @endif
                                <p class="text-[10px] text-gray-400 mt-1">{{ $log->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Waktu card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="space-y-2.5 info-row">
                    <div>
                        <dt>Tanggal Pengajuan</dt>
                        <dd>{{ $ticket->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($ticket->closed_at)
                    <div>
                        <dt>Ditutup</dt>
                        <dd>{{ $ticket->closed_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @endif
                </div>
            </div>

        </div>{{-- /right col --}}
    </div>
</div>
@endsection
