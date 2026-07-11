<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\InvoiceHelper;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Ticket;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct(private WhatsAppService $wa) {}

    public function dashboard()
    {
        $user    = Auth::user();
        $tickets = $user->tickets()->latest()->take(5)->get();
        $stats   = [
            'total'    => $user->tickets()->count(),
            'active'   => $user->tickets()->whereNotIn('status', ['COMPLETED', 'CLOSED', 'REJECTED'])->count(),
            'done'     => $user->tickets()->where('status', 'COMPLETED')->count(),
            'pending'  => $user->tickets()->where('status', 'PENDING')->count(),
        ];

        return view('customer.dashboard', compact('tickets', 'stats'));
    }

    public function index()
    {
        $tickets = Auth::user()->tickets()
            ->with(['technician.user', 'rating'])
            ->latest()
            ->paginate(10);

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('customer.tickets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category'       => ['required', 'in:KULKAS,TV,MESIN_CUCI'],
            'brand'          => ['nullable', 'string', 'max:100'],
            'description'    => ['required', 'string', 'max:1000'],
            'address'        => ['required', 'string', 'max:255'],
            'district'       => ['nullable', 'string', 'max:100'],
            'city'           => ['required', 'string', 'max:100'],
            'postal_code'    => ['nullable', 'string', 'max:10'],
            'address_notes'  => ['nullable', 'string', 'max:255'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'string'],
            'photos'         => ['nullable', 'array', 'max:5'],
            'photos.*'       => ['image', 'max:2048'],
        ]);

        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path        = $photo->store('tickets', 'public');
                $photoUrls[] = $path;
            }
        }

        $ticket = Ticket::create([
            'invoice_number' => InvoiceHelper::generate(),
            'customer_id'    => Auth::id(),
            'category'       => $data['category'],
            'brand'          => $data['brand'],
            'description'    => $data['description'],
            'address'        => $data['address'],
            'district'       => $data['district'] ?? null,
            'city'           => $data['city'],
            'postal_code'    => $data['postal_code'] ?? null,
            'address_notes'  => $data['address_notes'] ?? null,
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'],
            'photo_urls'     => $photoUrls,
            'status'         => 'PENDING',
        ]);

        $ticket->logs()->create([
            'old_status' => '',
            'new_status' => 'PENDING',
            'note'       => 'Tiket dibuat oleh pelanggan.',
        ]);

        $this->wa->sendTicketSubmitted($ticket);

        return redirect()->route('customer.tickets.show', $ticket)
            ->with('success', 'Tiket servis berhasil diajukan! No. Invoice: ' . $ticket->invoice_number);
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $ticket->load(['technician.user', 'logs', 'rating', 'waLogs']);

        return view('customer.tickets.show', compact('ticket'));
    }

    public function rate(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        if ($ticket->status !== 'COMPLETED' || $ticket->rating !== null) {
            return back()->with('error', 'Tiket tidak dapat dirating.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:500'],
        ]);

        Rating::create([
            'ticket_id'     => $ticket->id,
            'technician_id' => $ticket->technician_id,
            'customer_id'   => Auth::id(),
            'rating'        => $data['rating'],
            'review'        => $data['review'],
        ]);

        $ticket->technician->recalculateRating();

        $ticket->update(['status' => 'CLOSED', 'closed_at' => now()]);
        $ticket->logs()->create([
            'old_status' => 'COMPLETED',
            'new_status' => 'CLOSED',
            'note'       => 'Pelanggan memberikan rating ' . $data['rating'] . '/5.',
        ]);

        return redirect()->route('customer.tickets.show', $ticket)
            ->with('success', 'Terima kasih atas ulasan Anda!');
    }

    private function authorizeTicket(Ticket $ticket): void
    {
        if ($ticket->customer_id !== Auth::id()) {
            abort(403);
        }
    }
}
