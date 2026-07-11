@extends('layouts.app')
@section('title', 'Tiket Tugasan — Voltfix')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Semua Tiket Tugasan</h1>
        <p class="text-gray-500 text-sm mt-1">Riwayat lengkap tiket yang ditugaskan kepada Anda.</p>
    </div>

    @if($tickets->isEmpty())
        <div class="card text-center py-12">
            <p class="text-3xl mb-3">📋</p>
            <p class="text-gray-500 text-sm">Belum ada tiket yang ditugaskan.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($tickets as $ticket)
            <a href="{{ route('technician.ticket.show', $ticket) }}"
               class="card flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer block">
                <div class="text-2xl">{{ $ticket->category === 'KULKAS' ? '🧊' : ($ticket->category === 'TV' ? '📺' : '🌀') }}</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-sm text-gray-900">{{ $ticket->invoice_number }}</p>
                        @include('customer.partials.status-badge', ['status' => $ticket->status])
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->customer->name }} · {{ $ticket->preferred_date->format('d M Y') }} {{ $ticket->preferred_time }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endforeach
        </div>
        <div>{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
