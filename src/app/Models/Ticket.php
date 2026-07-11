<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'technician_id',
        'category',
        'brand',
        'photo_urls',
        'description',
        'address',
        'district',
        'city',
        'postal_code',
        'address_notes',
        'preferred_date',
        'preferred_time',
        'status',
        'rejection_reason',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'photo_urls'     => 'array',
            'preferred_date' => 'date',
            'closed_at'      => 'datetime',
        ];
    }

    // Status constants for convenience
    const STATUS_PENDING            = 'PENDING';
    const STATUS_REJECTED           = 'REJECTED';
    const STATUS_WAITING_ASSIGNMENT = 'WAITING_ASSIGNMENT';
    const STATUS_ASSIGNED           = 'ASSIGNED';
    const STATUS_ON_THE_WAY         = 'ON_THE_WAY';
    const STATUS_DIAGNOSIS          = 'DIAGNOSIS';
    const STATUS_WAITING_PART       = 'WAITING_PART';
    const STATUS_REPAIR             = 'REPAIR';
    const STATUS_COMPLETED          = 'COMPLETED';
    const STATUS_CLOSED             = 'CLOSED';

    public static function allStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_REJECTED,
            self::STATUS_WAITING_ASSIGNMENT,
            self::STATUS_ASSIGNED,
            self::STATUS_ON_THE_WAY,
            self::STATUS_DIAGNOSIS,
            self::STATUS_WAITING_PART,
            self::STATUS_REPAIR,
            self::STATUS_COMPLETED,
            self::STATUS_CLOSED,
        ];
    }

    /** Statuses the technician can advance to */
    public static function technicianNextStatuses(string $current): array
    {
        return match ($current) {
            'ASSIGNED'     => ['ON_THE_WAY'],
            'ON_THE_WAY'   => ['DIAGNOSIS'],
            'DIAGNOSIS'    => ['WAITING_PART', 'REPAIR'],
            'WAITING_PART' => ['REPAIR'],
            'REPAIR'       => ['COMPLETED'],
            default        => [],
        };
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function waLogs()
    {
        return $this->hasMany(WaLog::class);
    }

    public function isRatable(): bool
    {
        return $this->status === self::STATUS_COMPLETED && ! $this->rating()->exists();
    }
}
