<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'old_status',
        'new_status',
        'note',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
