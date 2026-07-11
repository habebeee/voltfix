<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_category',
        'average_rating',
        'is_available',
        'experience',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'average_rating' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function recalculateRating(): void
    {
        $avg = $this->ratings()->avg('rating') ?? 0;
        $this->update(['average_rating' => round($avg, 1)]);
    }
}
