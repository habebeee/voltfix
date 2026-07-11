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

    private function send(Ticket $ticket, string $phone, string $message): void
    {
        $status = 'failed';

        if (! empty($this->token)) {
            try {
                $response = $this->client->post($this->apiUrl, [
                    'headers' => ['Authorization' => $this->token],
                    'form_params' => [
                        'target'  => $phone,
                        'message' => $message,
                    ],
                ]);

                $body = json_decode($response->getBody()->getContents(), true);
                $status = ($body['status'] ?? false) ? 'sent' : 'failed';
            } catch (RequestException $e) {
                Log::error('WhatsApp send failed: ' . $e->getMessage());
            }
        } else {
            // Development: just log
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
}
