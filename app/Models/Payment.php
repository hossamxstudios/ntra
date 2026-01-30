<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'transaction_id',
        'machine_id',
        'imei_check_id',
        'mobile_device_id',
        'passenger_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'pos_reference',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = (string) Str::uuid();
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('receipt')
            ->singleFile();
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function imeiCheck(): BelongsTo
    {
        return $this->belongsTo(ImeiCheck::class);
    }

    public function mobileDevice(): BelongsTo
    {
        return $this->belongsTo(MobileDevice::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function markAsCompleted(string $posReference = null): void
    {
        $this->update([
            'status' => 'completed',
            'pos_reference' => $posReference,
            'paid_at' => now(),
        ]);

        if ($this->mobileDevice) {
            $this->mobileDevice->markAsPaid();
        }
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    public function refund(): void
    {
        $this->update(['status' => 'refunded']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByMachine($query, int $machineId)
    {
        return $query->where('machine_id', $machineId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
