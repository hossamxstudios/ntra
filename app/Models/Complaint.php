<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Complaint extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'machine_id',
        'name',
        'email',
        'phone',
        'national_id',
        'reason',
        'message',
        'other_reason',
        'status',
        'priority',
        'resolution',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function startProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    public function resolve(string $resolution): void
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_at' => now(),
        ]);
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }

    public function setPriority(string $priority): void
    {
        $this->update(['priority' => $priority]);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['new', 'in_progress']);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['new', 'in_progress']);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeByReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('national_id', 'like', "%{$search}%");
        });
    }
}
