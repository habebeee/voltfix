<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\InvoiceHelper;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\SiteSetting;
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
        $settings = SiteSetting::getHomeSettings();

        $categoryImages = [
            'HP'     => $this->publicImageUrl($settings['service_hp_image'] ?? null),
            'LAPTOP' => $this->publicImageUrl($settings['service_laptop_image'] ?? null),
            'TV'     => $this->publicImageUrl($settings['service_tv_image'] ?? null),
        ];

        return view('customer.tickets.create', compact('categoryImages'));
    }

    private function publicImageUrl(?string $path): ?string
    {
        $path = $this->normalizePublicPath($path);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        // Relative URL agar ikut host yang dipakai (desktop / mobile / IP)
        return '/storage/' . ltrim($path, '/');
    }

    private function normalizePublicPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = trim($path);

        if (str_starts_with($path, '[') || str_starts_with($path, '{')) {
            $decoded = json_decode($path, true);
            if (is_array($decoded)) {
                $path = (string) ($decoded[0] ?? reset($decoded) ?: '');
            }
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path !== '' ? $path : null;
    }

    public function store(Request $request)
    {
        $damageLabels = [
            'jatuh'     => 'Rusak karena jatuh',
            'air'       => 'Kecelup / kena air',
            'banting'   => 'Kebanting / terbentur',
            'mati_tiba' => 'Tiba-tiba mati / tidak nyala',
            'layar'     => 'Layar rusak / bergaris / blank',
            'baterai'   => 'Baterai bermasalah / cepat habis',
            'charge'    => 'Tidak bisa charge / port rusak',
            'lainnya'   => 'Lainnya',
        ];

        $data = $request->validate([
            'category'          => ['required', 'in:TV,HP,LAPTOP'],
            'brand'             => ['nullable', 'string', 'max:100'],
            'damage_cause'      => ['required', 'in:' . implode(',', array_keys($damageLabels))],
            'description_other' => ['required_if:damage_cause,lainnya', 'nullable', 'string', 'max:1000'],
            'address'           => ['required', 'string', 'max:255'],
            'district'          => ['nullable', 'string', 'max:100'],
            'city'              => ['required', 'string', 'max:100'],
            'postal_code'       => ['nullable', 'string', 'max:10'],
            'address_notes'     => ['nullable', 'string', 'max:255'],
            'preferred_date'    => ['required', 'date', 'after_or_equal:today'],
            'preferred_time'    => ['required', 'string'],
            'photos'            => ['required', 'array', 'min:1', 'max:5'],
            'photos.*'          => ['required', 'image', 'max:2048'],
        ], [
            'category.required'            => 'Pilih jenis perangkat terlebih dahulu.',
            'damage_cause.required'        => 'Pilih penyebab / jenis kerusakan terlebih dahulu.',
            'description_other.required_if'=> 'Jelaskan masalahnya jika memilih Lainnya.',
            'photos.required'              => 'Foto kerusakan wajib diupload minimal 1 foto.',
            'photos.min'                   => 'Foto kerusakan wajib diupload minimal 1 foto.',
            'photos.*.image'               => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'photos.*.max'                 => 'Ukuran foto maksimal 2MB per file.',
        ]);

        $description = $data['damage_cause'] === 'lainnya'
            ? 'Lainnya: ' . trim($data['description_other'])
            : $damageLabels[$data['damage_cause']];

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
            'description'    => $description,
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
