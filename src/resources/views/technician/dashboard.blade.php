@extends('layouts.app')
@section('title', 'Dashboard Teknisi — Voltfix')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Teknisi</h1>
            <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}. Keahlian: <strong>{{ \App\Helpers\CategoryHelper::label($technician->skill_category) }}</strong></p>
        </div>
        <a href="{{ route('technician.profile.edit') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 px-4 py-2 rounded-xl transition-all self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Edit Profil
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Sedang Dikerjakan', 'value' => $stats['active'],    'color' => 'blue',   'emoji' => '🔧'],
            ['label' => 'Selesai',            'value' => $stats['completed'], 'color' => 'green',  'emoji' => '✅'],
            ['label' => 'Total Tiket',        'value' => $stats['total'],     'color' => 'purple', 'emoji' => '📋'],
            ['label' => 'Rating Rata-rata',   'value' => $stats['rating'] . ' ⭐', 'color' => 'yellow', 'emoji' => '⭐'],
        ] as $stat)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-2xl mb-1">{{ $stat['emoji'] }}</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Active tickets --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Tiket Aktif ({{ $activeTickets->count() }})</h2>
            <a href="{{ route('technician.tickets') }}" class="text-sm text-blue-600 hover:text-blue-700">Semua tiket →</a>
        </div>

        @if($activeTickets->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <p class="text-3xl mb-2">🎉</p>
                <p class="text-sm">Tidak ada tiket aktif saat ini.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($activeTickets as $ticket)
                <a href="{{ route('technician.ticket.show', $ticket) }}"
                   class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors cursor-pointer block">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl
                        {{ $ticket->category === 'TV' ? 'bg-violet-100' : ($ticket->category === 'HP' ? 'bg-amber-100' : 'bg-sky-100') }}">
                        {{ $ticket->category === 'TV' ? '📺' : ($ticket->category === 'HP' ? '📱' : '💻') }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $ticket->invoice_number }}</p>
                        <p class="text-xs text-gray-500">{{ $ticket->customer->name }} · {{ $ticket->preferred_date->format('d M Y') }}</p>
                    </div>
                    @php
                        $statusColors = ['ASSIGNED'=>'bg-indigo-100 text-indigo-700','ON_THE_WAY'=>'bg-purple-100 text-purple-700','DIAGNOSIS'=>'bg-cyan-100 text-cyan-700','WAITING_PART'=>'bg-orange-100 text-orange-700','REPAIR'=>'bg-violet-100 text-violet-700'];
                    @endphp
                    <span class="badge-status {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }} text-xs">
                        {{ str_replace('_', ' ', $ticket->status) }}
                    </span>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
