<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct(private WhatsAppService $wa) {}

    public function dashboard()
    {
        $technician = Auth::user()->technician;

        if (! $technician) {
            abort(403, 'Profil teknisi tidak ditemukan.');
        }

        $stats = [
            'active'    => $technician->tickets()->whereIn('status', ['ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'WAITING_PART', 'REPAIR'])->count(),
            'completed' => $technician->tickets()->where('status', 'COMPLETED')->count(),
            'total'     => $technician->tickets()->count(),
            'rating'    => $technician->average_rating,
        ];

        $activeTickets = $technician->tickets()
            ->with('customer')
            ->whereIn('status', ['ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'WAITING_PART', 'REPAIR'])
            ->orderBy('created_at')
            ->get();

        return view('technician.dashboard', compact('stats', 'activeTickets', 'technician'));
    }

    public function index()
    {
        $technician = Auth::user()->technician;

        $tickets = $technician->tickets()
            ->with('customer')
            ->latest()
            ->paginate(15);

        return view('technician.tickets', compact('tickets', 'technician'));
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTechnicianTicket($ticket);
        $ticket->load(['customer', 'logs', 'rating']);

        return view('technician.ticket-detail', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorizeTechnicianTicket($ticket);

        $allowedNext = Ticket::technicianNextStatuses($ticket->status);

        $data = $request->validate([
            'new_status' => ['required', 'in:' . implode(',', $allowedNext)],
            'note'       => ['nullable', 'string', 'max:500'],
        ]);

        $old = $ticket->status;
        $ticket->update(['status' => $data['new_status']]);
        $ticket->logs()->create([
            'old_status' => $old,
            'new_status' => $data['new_status'],
            'note'       => $data['note'] ?? null,
        ]);

        if ($data['new_status'] === 'COMPLETED') {
            $this->wa->sendTicketCompleted($ticket);
        }

        return back()->with('success', 'Status tiket berhasil diperbarui ke ' . $data['new_status'] . '.');
    }

    private function authorizeTechnicianTicket(Ticket $ticket): void
    {
        $technicianId = Auth::user()->technician?->id;
        if ($ticket->technician_id !== $technicianId) {
            abort(403);
        }
    }
}
