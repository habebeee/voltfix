@extends('layouts.app')
@section('title', 'Tiket Saya — VoltFix')

@section('content')
<div class="space-y-5 sm:space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tiket Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau status semua pengajuan servis Anda.</p>
        </div>
        <a href="{{ route('customer.tickets.create') }}"
           class="inline-flex items-center justify-center gap-1.5 self-stretch sm:self-auto bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-orange-200 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Ajukan Baru
        </a>
    </div>

    @if($tickets->isEmpty())
        <div class="card text-center py-14 sm:py-16 px-4">
            <div class="text-5xl mb-4">📋</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada tiket</h3>
            <p class="text-gray-500 text-sm mb-6">Mulai dengan mengajukan tiket servis pertama Anda.</p>
            <a href="{{ route('customer.tickets.create') }}" class="btn-primary inline-block">Ajukan Servis</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($tickets as $ticket)
            <a href="{{ route('customer.tickets.show', $ticket) }}"
               class="card !p-4 sm:!p-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4 hover:shadow-md transition-shadow cursor-pointer block">
                <div class="flex items-start gap-3 min-w-0 flex-1">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full flex items-center justify-center flex-shrink-0 text-xl sm:text-2xl
                        {{ $ticket->category === 'TV' ? 'bg-violet-100' : ($ticket->category === 'HP' ? 'bg-amber-100' : 'bg-sky-100') }}">
                        {{ $ticket->category === 'TV' ? '📺' : ($ticket->category === 'HP' ? '📱' : '💻') }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $ticket->invoice_number }}</p>
                            @include('customer.partials.status-badge', ['status' => $ticket->status])
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5 line-clamp-2 sm:truncate">{{ $ticket->description }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $ticket->category }}{{ $ticket->brand ? ' · ' . $ticket->brand : '' }} ·
                            {{ $ticket->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-2 flex-shrink-0 pl-14 sm:pl-0 border-t border-gray-50 pt-2.5 sm:border-0 sm:pt-0">
                    <div class="text-left sm:text-right min-w-0">
                        @if($ticket->status === 'COMPLETED' && !$ticket->rating)
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-medium">Beri Rating</span>
                        @elseif($ticket->technician)
                            <p class="text-xs text-gray-500">Teknisi</p>
                            <p class="text-xs font-medium text-gray-700 truncate max-w-[11rem]">{{ $ticket->technician->user->name }}</p>
                        @else
                            <p class="text-xs text-gray-400">Belum ada teknisi</p>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
