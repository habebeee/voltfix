<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\WaLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private Client $client;
    private string $apiUrl;
    private string $token;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 10]);
        $this->apiUrl = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $this->token  = config('services.fonnte.token', '');
    }

    public function sendTicketSubmitted(Ticket $ticket): void
    {
        $customer = $ticket->customer;
        $message  = "Halo *{$customer->name}*,\n\n"
            . "Tiket servis Anda telah kami terima.\n"
            . "No. Invoice: *{$ticket->invoice_number}*\n"
            . "Kategori: *{$ticket->category}*\n"
            . "Status: *MENUNGGU KONFIRMASI ADMIN*\n\n"
            . "Kami akan segera memproses pengajuan Anda. Terima kasih!";

        $this->send($ticket, $customer->phone, $message);
    }

    public function sendTicketCompleted(Ticket $ticket): void
    {
        $customer  = $ticket->customer;
        $technician = $ticket->technician?->user?->name ?? '-';
        $message   = "Halo *{$customer->name}*,\n\n"
            . "Tiket servis Anda telah *SELESAI* dikerjakan.\n"
            . "No. Invoice: *{$ticket->invoice_number}*\n"
            . "Teknisi: *{$technician}*\n\n"
            . "Mohon berikan rating & ulasan untuk teknisi kami melalui aplikasi.\n"
            . "Terima kasih telah menggunakan layanan kami!";

        $this->send($ticket, $customer->phone, $message);
    }

    public function sendTicketApproved(Ticket $ticket): void
    {
        $customer = $ticket->customer;
        $message  = "Halo *{$customer->name}*,\n\n"
            . "Tiket servis Anda telah *DISETUJUI* admin.\n"
            . "No. Invoice: *{$ticket->invoice_number}*\n"
            . "Status: *MENUNGGU PENUGASAN TEKNISI*\n\n"
            . "Kami akan segera menugaskan teknisi terbaik untuk Anda.";

        $this->send($ticket, $customer->phone, $message);
    }

    public function sendTicketAssigned(Ticket $ticket): void
    {
        $customer   = $ticket->customer;
        $technician = $ticket->technician?->user?->name ?? '-';
        $message    = "Halo *{$customer->name}*,\n\n"
            . "Teknisi telah ditugaskan untuk tiket Anda.\n"
            . "No. Invoice: *{$ticket->invoice_number}*\n"
            . "Teknisi: *{$technician}*\n"
            . "Status: *TEKNISI DITUGASKAN*\n\n"
            . "Teknisi akan menghubungi Anda sesuai jadwal servis.";

        $this->send($ticket, $customer->phone, $message);
    }

    public function sendTicketRejected(Ticket $ticket, string $reason): void
    {
        $customer = $ticket->customer;
        $message  = "Halo *{$customer->name}*,\n\n"
            . "Maaf, tiket servis Anda *DITOLAK*.\n"
            . "No. Invoice: *{$ticket->invoice_number}*\n"
            . "Alasan: *{$reason}*\n\n"
            . "Silakan hubungi kami jika ada pertanyaan.";

        $this->send($ticket, $customer->phone, $message);
    }

    private function send(Ticket $ticket, string $phone, string $message): void
    {
        $phone  = $this->normalizePhone($phone);
        $status = 'failed';

        if ($phone === '') {
            Log::warning("WhatsApp skipped: nomor kosong untuk tiket #{$ticket->id}");
            $status = 'skipped';
        } elseif (! empty($this->token)) {
            try {
                $response = $this->client->post($this->apiUrl, [
                    'headers' => ['Authorization' => $this->token],
                    'form_params' => [
                        'target'      => $phone,
                        'message'     => $message,
                        'countryCode' => '62',
                    ],
                ]);

                $body = json_decode($response->getBody()->getContents(), true);
                $status = ($body['status'] ?? false) ? 'sent' : 'failed';

                if ($status === 'failed') {
                    Log::warning('WhatsApp API rejected', [
                        'ticket_id' => $ticket->id,
                        'phone'     => $phone,
                        'response'  => $body,
                    ]);
                }
            } catch (RequestException $e) {
                Log::error('WhatsApp send failed: ' . $e->getMessage(), [
                    'ticket_id' => $ticket->id,
                    'phone'     => $phone,
                ]);
            }
        } else {
            Log::info("WhatsApp (dev) → {$phone}: {$message}");
            $status = 'dev_logged';
        }

        WaLog::create([
            'ticket_id' => $ticket->id,
            'phone'     => $phone,
            'message'   => $message,
            'status'    => $status,
        ]);
    }

    /** Normalize to Fonnte format: digits only, 62xxxxxxxxxx */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
