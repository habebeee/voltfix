@extends('layouts.app')
@section('title', 'Tiket Saya — Voltfix')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tiket Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau status semua pengajuan servis Anda.</p>
        </div>
        <a href="{{ route('customer.tickets.create') }}" class="btn-primary text-sm">+ Ajukan Baru</a>
    </div>

    @if($tickets->isEmpty())
        <div class="card text-center py-16">
            <div class="text-5xl mb-4">📋</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada tiket</h3>
            <p class="text-gray-500 text-sm mb-6">Mulai dengan mengajukan tiket servis pertama Anda.</p>
            <a href="{{ route('customer.tickets.create') }}" class="btn-primary inline-block">Ajukan Servis</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($tickets as $ticket)
            <a href="{{ route('customer.tickets.show', $ticket) }}"
               class="card flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer block">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 text-2xl
                    {{ $ticket->category === 'KULKAS' ? 'bg-blue-100' : ($ticket->category === 'TV' ? 'bg-purple-100' : 'bg-teal-100') }}">
                    {{ $ticket->category === 'KULKAS' ? '🧊' : ($ticket->category === 'TV' ? '📺' : '🌀') }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-900 text-sm">{{ $ticket->invoice_number }}</p>
                        @include('customer.partials.status-badge', ['status' => $ticket->status])
                    </div>
                    <p class="text-sm text-gray-600 mt-0.5 truncate">{{ $ticket->description }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $ticket->category }}{{ $ticket->brand ? ' · ' . $ticket->brand : '' }} ·
                        {{ $ticket->created_at->format('d M Y') }}
                    </p>
                </div>

                <div class="flex-shrink-0 text-right">
                    @if($ticket->status === 'COMPLETED' && !$ticket->rating)
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-medium">Beri Rating</span>
                    @elseif($ticket->technician)
                        <p class="text-xs text-gray-500">Teknisi:</p>
                        <p class="text-xs font-medium text-gray-700">{{ $ticket->technician->user->name }}</p>
                    @endif
                    <svg class="w-4 h-4 text-gray-400 mt-1 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>

        <div>{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
