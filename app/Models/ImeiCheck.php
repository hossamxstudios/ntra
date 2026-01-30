<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ImeiCheck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'machine_id',
        'mobile_device_id',
        'passenger_id',
        'scanned_imei',
        'phone_serial',
        'status',
        'api_response',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function mobileDevice(): BelongsTo
    {
        return $this->belongsTo(MobileDevice::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'checked_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByMachine($query, int $machineId)
    {
        return $query->where('machine_id', $machineId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
