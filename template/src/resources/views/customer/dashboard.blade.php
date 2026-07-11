@extends('layouts.app')
@section('title', 'Dashboard — Voltfix')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- ── Greeting ─────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                Selamat datang, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-400 text-sm mt-1">Kelola dan pantau servis elektronik Anda dari sini.</p>
        </div>
        <a href="{{ route('customer.tickets.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-300 transition-all self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Servis
        </a>
    </div>

    {{-- ── Stats ────────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $statCards = [
            [
                'label'  => 'Total Tiket',
                'value'  => $stats['total'],
                'from'   => 'from-blue-500',
                'to'     => 'to-blue-600',
                'shadow' => 'shadow-blue-100',
                'bg'     => 'bg-blue-50',
                'ring'   => 'ring-blue-100',
                'svg'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
            ],
            [
                'label'  => 'Sedang Proses',
                'value'  => $stats['active'],
                'from'   => 'from-amber-400',
                'to'     => 'to-orange-500',
                'shadow' => 'shadow-amber-100',
                'bg'     => 'bg-amber-50',
                'ring'   => 'ring-amber-100',
                'svg'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ],
            [
                'label'  => 'Selesai',
                'value'  => $stats['done'],
                'from'   => 'from-emerald-400',
                'to'     => 'to-teal-500',
                'shadow' => 'shadow-emerald-100',
                'bg'     => 'bg-emerald-50',
                'ring'   => 'ring-emerald-100',
                'svg'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ],
            [
                'label'  => 'Menunggu',
                'value'  => $stats['pending'],
                'from'   => 'from-violet-400',
                'to'     => 'to-purple-600',
                'shadow' => 'shadow-violet-100',
                'bg'     => 'bg-violet-50',
                'ring'   => 'ring-violet-100',
                'svg'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
            ],
        ];
        @endphp

        @foreach($statCards as $card)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} flex items-center justify-center shadow-md {{ $card['shadow'] }} ring-4 {{ $card['ring'] }} flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $card['svg'] !!}</svg>
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900 leading-none mb-0.5">{{ $card['value'] }}</div>
                <div class="text-xs text-gray-400 font-medium">{{ $card['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── CTA Banner ───────────────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-5"
         style="background: linear-gradient(135deg, #2563EB 0%, #4F46E5 60%, #7C3AED 100%);">
        {{-- Decorative blobs --}}
        <div class="absolute -top-6 -right-6 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-4 right-24 w-20 h-20 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute top-2 left-1/2 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>

        <div class="relative">
            <p class="text-white font-bold text-lg leading-tight">Butuh servis elektronik?</p>
            <p class="text-blue-100 text-sm mt-1">Ajukan tiket sekarang dan teknisi kami siap membantu.</p>
        </div>
        <a href="{{ route('customer.tickets.create') }}"
           class="relative flex-shrink-0 inline-flex items-center gap-2 bg-white text-blue-700 hover:bg-blue-50 font-semibold text-sm px-5 py-2.5 rounded-xl shadow-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Servis
        </a>
    </div>

    {{-- ── Recent Tickets ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h2 class="font-bold text-gray-900 text-sm">Tiket Terbaru</h2>
            </div>
            <a href="{{ route('customer.tickets.index') }}"
               class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
                Lihat semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($tickets->isEmpty())
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada tiket servis</p>
                <p class="text-xs text-gray-400 mb-5 max-w-xs">Ajukan tiket pertama Anda dan kami akan segera menghubungi teknisi.</p>
                <a href="{{ route('customer.tickets.create') }}"
                   class="inline-flex items-center gap-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-md shadow-blue-200 hover:shadow-blue-300 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Sekarang
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @php
                $categoryMeta = [
                    'KULKAS'     => ['emoji' => '🧊', 'bg' => 'bg-blue-50',   'text' => 'text-blue-600'],
                    'TV'         => ['emoji' => '📺', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                    'MESIN_CUCI' => ['emoji' => '🌀', 'bg' => 'bg-teal-50',   'text' => 'text-teal-600'],
                ];
                $statusMeta = [
                    'PENDING'            => ['label' => 'Menunggu Konfirmasi', 'dot' => 'bg-amber-400',   'bg' => 'bg-amber-50',   'text' => 'text-amber-700'],
                    'REJECTED'           => ['label' => 'Ditolak',             'dot' => 'bg-red-400',     'bg' => 'bg-red-50',     'text' => 'text-red-700'],
                    'WAITING_ASSIGNMENT' => ['label' => 'Cari Teknisi',        'dot' => 'bg-blue-400',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700'],
                    'ASSIGNED'           => ['label' => 'Ditugaskan',          'dot' => 'bg-indigo-400',  'bg' => 'bg-indigo-50',  'text' => 'text-indigo-700'],
                    'ON_THE_WAY'         => ['label' => 'Dalam Perjalanan',    'dot' => 'bg-violet-400',  'bg' => 'bg-violet-50',  'text' => 'text-violet-700'],
                    'DIAGNOSIS'          => ['label' => 'Diagnosa',            'dot' => 'bg-cyan-400',    'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700'],
                    'WAITING_PART'       => ['label' => 'Tunggu Spare Part',   'dot' => 'bg-orange-400',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700'],
                    'REPAIR'             => ['label' => 'Diperbaiki',          'dot' => 'bg-purple-400',  'bg' => 'bg-purple-50',  'text' => 'text-purple-700'],
                    'COMPLETED'          => ['label' => 'Selesai',             'dot' => 'bg-emerald-400', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                    'CLOSED'             => ['label' => 'Ditutup',             'dot' => 'bg-gray-300',    'bg' => 'bg-gray-100',   'text' => 'text-gray-500'],
                ];
                @endphp

                @foreach($tickets as $ticket)
                @php
                $cat  = $categoryMeta[$ticket->category]  ?? ['emoji' => '🔧', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                $stat = $statusMeta[$ticket->status]      ?? ['label' => $ticket->status, 'dot' => 'bg-gray-300', 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                @endphp
                <a href="{{ route('customer.tickets.show', $ticket) }}"
                   class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/70 transition-colors group">

                    {{-- Category icon --}}
                    <div class="w-10 h-10 rounded-xl {{ $cat['bg'] }} flex items-center justify-center text-xl flex-shrink-0">
                        {{ $cat['emoji'] }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $ticket->invoice_number }}</p>
                            @if($ticket->status === 'COMPLETED' && !$ticket->rating)
                            <span class="flex-shrink-0 text-xs bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full font-medium">⭐ Beri Rating</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $ticket->brand ?? $ticket->category }} · {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Status badge --}}
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium {{ $stat['bg'] }} {{ $stat['text'] }} px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full {{ $stat['dot'] }}"></span>
                            {{ $stat['label'] }}
                        </span>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
