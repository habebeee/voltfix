<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'phone',
        'message',
        'status',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
