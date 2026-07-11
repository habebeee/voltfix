@use('Illuminate\Support\Facades\Storage')
@extends('layouts.app')
@section('title', 'Tiket ' . $ticket->invoice_number . ' — Voltfix')

@push('head')
<style>
    .info-label { font-size:11px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:3px; }
    .info-value { font-size:14px; color:#111827; font-weight:500; }

    /* Status action buttons */
    .status-btn input[type=radio]:checked + div {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px -2px var(--btn-shadow);
    }
</style>
@endpush

@section('content')
@php
$statusLabels = [
    'PENDING'            => 'Menunggu Konfirmasi',
    'REJECTED'           => 'Ditolak',
    'WAITING_ASSIGNMENT' => 'Mencari Teknisi',
    'ASSIGNED'           => 'Ditugaskan',
    'ON_THE_WAY'         => 'Dalam Perjalanan',
    'DIAGNOSIS'          => 'Diagnosa',
    'WAITING_PART'       => 'Tunggu Spare Part',
    'REPAIR'             => 'Perbaikan',
    'COMPLETED'          => 'Selesai',
    'CLOSED'             => 'Ditutup',
];
$statusPill = [
    'PENDING'            => 'bg-amber-50 text-amber-700 border-amber-200',
    'REJECTED'           => 'bg-red-50 text-red-700 border-red-200',
    'WAITING_ASSIGNMENT' => 'bg-blue-50 text-blue-700 border-blue-200',
    'ASSIGNED'           => 'bg-indigo-50 text-indigo-700 border-indigo-200',
    'ON_THE_WAY'         => 'bg-violet-50 text-violet-700 border-violet-200',
    'DIAGNOSIS'          => 'bg-cyan-50 text-cyan-700 border-cyan-200',
    'WAITING_PART'       => 'bg-orange-50 text-orange-700 border-orange-200',
    'REPAIR'             => 'bg-purple-50 text-purple-700 border-purple-200',
    'COMPLETED'          => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'CLOSED'             => 'bg-gray-100 text-gray-500 border-gray-200',
];
$statusDot = [
    'PENDING'=>'bg-amber-400','REJECTED'=>'bg-red-400','WAITING_ASSIGNMENT'=>'bg-blue-400',
    'ASSIGNED'=>'bg-indigo-400','ON_THE_WAY'=>'bg-violet-400','DIAGNOSIS'=>'bg-cyan-400',
    'WAITING_PART'=>'bg-orange-400','REPAIR'=>'bg-purple-400','COMPLETED'=>'bg-emerald-400','CLOSED'=>'bg-gray-300',
];
$statusStrip = [
    'PENDING'=>'#F59E0B','REJECTED'=>'#EF4444','WAITING_ASSIGNMENT'=>'#3B82F6',
    'ASSIGNED'=>'#6366F1','ON_THE_WAY'=>'#8B5CF6','DIAGNOSIS'=>'#06B6D4',
    'WAITING_PART'=>'#F97316','REPAIR'=>'#9333EA','COMPLETED'=>'#10B981','CLOSED'=>'#9CA3AF',
];
$catLabel = ['KULKAS'=>'Kulkas','TV'=>'TV','MESIN_CUCI'=>'Mesin Cuci'][$ticket->category] ?? $ticket->category;
$nextStatuses = \App\Models\Ticket::technicianNextStatuses($ticket->status);

$actionConfig = [
    'ON_THE_WAY'   => ['label'=>'Dalam Perjalanan', 'style'=>'background:#7C3AED;--btn-shadow:rgba(124,58,237,.4)',
        'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>'],
    'DIAGNOSIS'    => ['label'=>'Mulai Diagnosa',    'style'=>'background:#0891B2;--btn-shadow:rgba(8,145,178,.4)',
        'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>'],
    'WAITING_PART' => ['label'=>'Tunggu Spare Part', 'style'=>'background:#EA580C;--btn-shadow:rgba(234,88,12,.4)',
        'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    'REPAIR'       => ['label'=>'Mulai Perbaikan',   'style'=>'background:#7C3AED;--btn-shadow:rgba(124,58,237,.4)',
        'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    'COMPLETED'    => ['label'=>'Tandai Selesai',    'style'=>'background:#059669;--btn-shadow:rgba(5,150,105,.4)',
        'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
];
@endphp

<div class="max-w-3xl mx-auto space-y-5">

    {{-- ── Header ────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5" style="background:{{ $statusStrip[$ticket->status] ?? '#9CA3AF' }}"></div>
        <div class="p-5 flex items-start gap-4">
            <a href="{{ route('technician.dashboard') }}"
               class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100 flex items-center justify-center transition-colors mt-0.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl font-black text-gray-900 tracking-tight">{{ $ticket->invoice_number }}</h1>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold border px-2.5 py-1 rounded-full {{ $statusPill[$ticket->status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot[$ticket->status] ?? 'bg-gray-300' }}"></span>
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </span>
                </div>
                <p class="text-xs text-gray-400">
                    <span class="font-medium text-gray-600">{{ $catLabel }}{{ $ticket->brand ? ' — '.$ticket->brand : '' }}</span>
                    · Jadwal {{ $ticket->preferred_date->format('d M Y') }}, {{ $ticket->preferred_time }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── Kolom kiri (2/3) ────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info pelanggan & perangkat --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#2563EB,#1D4ED8)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Informasi Pelanggan & Perangkat</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="info-label">Pelanggan</p>
                            <p class="info-value">{{ $ticket->customer->name }}</p>
                        </div>
                        <div>
                            <p class="info-label">No. WhatsApp</p>
                            <a href="https://wa.me/{{ $ticket->customer->phone }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-600 hover:text-green-700 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                {{ $ticket->customer->phone }}
                            </a>
                        </div>
                        <div>
                            <p class="info-label">Jenis Elektronik</p>
                            <p class="info-value">{{ $catLabel }}{{ $ticket->brand ? ' — '.$ticket->brand : '' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Jadwal Kunjungan</p>
                            <p class="info-value">{{ $ticket->preferred_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->preferred_time }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="info-label">Deskripsi Keluhan</p>
                        <div class="mt-1.5 bg-gray-50 border border-gray-100 rounded-xl p-3.5 text-sm text-gray-800 leading-relaxed">
                            {{ $ticket->description }}
                        </div>
                    </div>
                </div>
            </div>

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
                            <div class="absolute inset-0 rounded-xl bg-black/0 group-hover:bg-black/15 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Perbarui Status --}}
            @if(count($nextStatuses) > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50"
                     style="background:linear-gradient(135deg,#EFF6FF,#F5F3FF)">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#6366F1,#4F46E5)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Perbarui Status</h2>
                        <p class="text-xs text-gray-400">Pilih tahap berikutnya dan konfirmasi</p>
                    </div>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('technician.ticket.update-status', $ticket) }}"
                          class="space-y-5" x-data="{ selected: '' }">
                        @csrf
                        @method('PATCH')

                        {{-- Action buttons --}}
                        <div class="grid grid-cols-1 sm:grid-cols-{{ count($nextStatuses) > 2 ? '3' : count($nextStatuses) }} gap-3">
                            @foreach($nextStatuses as $status)
                            @php $ac = $actionConfig[$status] ?? ['label'=>$status,'style'=>'background:#6366F1;--btn-shadow:rgba(99,102,241,.4)','svg'=>'']; @endphp
                            <label class="cursor-pointer status-btn" x-on:click="selected='{{ $status }}'">
                                <input type="radio" name="new_status" value="{{ $status }}" class="sr-only" required>
                                <div class="relative flex flex-col items-center gap-2 p-4 rounded-2xl text-white text-center transition-all duration-200 border-2 border-transparent"
                                     style="{{ $ac['style'] }}"
                                     :class="selected==='{{ $status }}' ? 'ring-4 ring-offset-2' : 'opacity-80 hover:opacity-100'"
                                     :style="selected==='{{ $status }}' ? 'ring-color:rgba(255,255,255,.5);transform:translateY(-3px);box-shadow:0 8px 20px -4px var(--btn-shadow)' : ''">
                                    <svg class="w-6 h-6 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $ac['svg'] !!}</svg>
                                    <span class="text-xs font-bold leading-tight">{{ $ac['label'] }}</span>
                                    {{-- Check indicator --}}
                                    <div class="absolute -top-1.5 -right-1.5 w-4.5 h-4.5 bg-white rounded-full flex items-center justify-center shadow transition-all duration-200"
                                         style="width:18px;height:18px"
                                         :class="selected==='{{ $status }}' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'">
                                        <svg class="w-2.5 h-2.5 text-emerald-600" style="width:10px;height:10px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1.5 block">
                                Catatan Teknis
                                <span class="font-normal text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded text-[10px] ml-1">opsional</span>
                            </label>
                            <textarea name="note" rows="2"
                                      placeholder="Tambahkan catatan teknis, temuan, atau keterangan lain..."
                                      class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 bg-gray-50 resize-none transition-all"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-lg"
                                style="background:linear-gradient(135deg,#4F46E5,#4338CA);box-shadow:0 6px 18px -4px rgba(79,70,229,.4)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            Konfirmasi Perubahan Status
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Rating diterima --}}
            @if($ticket->rating)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md bg-amber-400 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Rating dari Pelanggan</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-1.5 mb-2">
                        @for($i=1;$i<=5;$i++)
                        <span class="text-2xl {{ $i<=$ticket->rating->rating?'text-amber-400':'text-gray-200' }}">★</span>
                        @endfor
                        <span class="ml-1 text-base font-black text-gray-800">{{ $ticket->rating->rating }}<span class="text-xs font-normal text-gray-400">/5</span></span>
                    </div>
                    @if($ticket->rating->review)
                    <p class="text-sm text-gray-600 italic bg-amber-50 border border-amber-100 rounded-xl p-3">"{{ $ticket->rating->review }}"</p>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- /left col --}}

        {{-- ── Kolom kanan (1/3) ────────────────────────────────────────── --}}
        <div class="space-y-5">

            {{-- Alamat --}}
            @if($ticket->address)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#F59E0B,#EF4444)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Alamat Tujuan</h2>
                </div>
                <div class="p-5">
                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100 rounded-xl p-4 space-y-1 mb-3">
                        <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $ticket->address }}</p>
                        @if($ticket->district)
                        <p class="text-xs text-gray-500">{{ $ticket->district }}</p>
                        @endif
                        <p class="text-xs font-medium text-gray-600">{{ $ticket->city }}{{ $ticket->postal_code?', '.$ticket->postal_code:'' }}</p>
                        @if($ticket->address_notes)
                        <p class="text-xs text-orange-600 font-medium pt-1">📍 {{ $ticket->address_notes }}</p>
                        @endif
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($ticket->address??'').', '.($ticket->city??'')) }}"
                       target="_blank"
                       class="flex items-center justify-center gap-2 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl transition-colors w-full shadow-sm shadow-blue-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka di Google Maps
                    </a>
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-50">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#6366F1,#4F46E5)">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800">Riwayat Status</h2>
                </div>
                <div class="p-5">
                    <div class="relative pl-5">
                        <div class="absolute left-1.5 top-2 bottom-2 w-px bg-gray-100"></div>
                        @foreach($ticket->logs->sortByDesc('created_at') as $i => $log)
                        @php
                        $dot = match($log->new_status){
                            'COMPLETED'=>'bg-emerald-400','REJECTED'=>'bg-red-400','PENDING'=>'bg-amber-400',default=>'bg-indigo-400'
                        };
                        @endphp
                        <div class="relative mb-3.5 last:mb-0">
                            <div class="absolute -left-5 top-1.5 w-3 h-3 rounded-full {{ $dot }} border-2 border-white shadow-sm"></div>
                            <div class="bg-gray-50 rounded-xl px-3 py-2.5 border border-gray-100">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <p class="text-xs font-bold text-gray-800">{{ $statusLabels[$log->new_status] ?? $log->new_status }}</p>
                                    @if($i===0)
                                    <span class="text-[10px] bg-indigo-100 text-indigo-600 font-bold px-1.5 py-0.5 rounded-full flex-shrink-0">Now</span>
                                    @endif
                                </div>
                                @if($log->note)<p class="text-[11px] text-gray-500 leading-snug">{{ $log->note }}</p>@endif
                                <p class="text-[10px] text-gray-400 mt-1">{{ $log->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>{{-- /right col --}}
    </div>
</div>
@endsection
