<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Suggestion extends Model implements HasMedia
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
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function markAsReviewed(): void
    {
        $this->update(['status' => 'reviewed']);
    }

    public function markAsAddressed(): void
    {
        $this->update(['status' => 'addressed']);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
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
