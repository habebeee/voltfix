<?php

namespace App\Helpers;

use App\Models\Ticket;

class InvoiceHelper
{
    public static function generate(): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $last   = Ticket::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_number');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
