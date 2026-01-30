<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileDevice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'passenger_id',
        'device_type',
        'brand',
        'model',
        'imei_number',
        'imei_number_2',
        'imei_number_3',
        'serial_number',
        'tax',
        'registered_at',
        'is_activated',
        'activated_at',
        'is_locked',
        'is_paid',
    ];

    protected $casts = [
        'tax' => 'decimal:2',
        'registered_at' => 'datetime',
        'activated_at' => 'datetime',
        'is_activated' => 'boolean',
        'is_locked' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function imeiChecks(): HasMany
    {
        return $this->hasMany(ImeiCheck::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getAllImeiNumbers(): array
    {
        return array_filter([
            $this->imei_number,
            $this->imei_number_2,
            $this->imei_number_3,
        ]);
    }

    public function activate(): void
    {
        $this->update([
            'is_activated' => true,
            'activated_at' => now(),
        ]);
    }

    public function markAsPaid(): void
    {
        $this->update(['is_paid' => true]);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopeByImei($query, string $imei)
    {
        return $query->where(function ($q) use ($imei) {
            $q->where('imei_number', $imei)
              ->orWhere('imei_number_2', $imei)
              ->orWhere('imei_number_3', $imei);
        });
    }

    public function scopeActivated($query)
    {
        return $query->where('is_activated', true);
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }
}
