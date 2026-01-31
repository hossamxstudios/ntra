<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Passenger extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'imei_check_id',
        'first_name',
        'last_name',
        'birthdate',
        'gender',
        'nationality',
        'issue_state',
        'address',
        'document_number',
        'document_type',
        'valid_until',
        'mrz1',
        'mrz2',
        'mrz3',
        'national_id',
        'passport_no',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'valid_until' => 'date',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('passenger_photo')
            ->singleFile();

        $this->addMediaCollection('passport_document')
            ->singleFile();

        $this->addMediaCollection('arrival_stamp')
            ->singleFile();

        $this->addMediaCollection('boarding_card')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10);
    }

    public function imeiCheck(): BelongsTo
    {
        return $this->belongsTo(ImeiCheck::class);
    }

    public function mobileDevices(): HasMany
    {
        return $this->hasMany(MobileDevice::class);
    }

    public function imeiChecks(): HasMany
    {
        return $this->hasMany(ImeiCheck::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFullMrzAttribute(): string
    {
        return implode("\n", array_filter([
            $this->mrz1,
            $this->mrz2,
            $this->mrz3,
        ]));
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('national_id', 'like', "%{$search}%")
              ->orWhere('passport_no', 'like', "%{$search}%");
        });
    }

    public function scopeByNationality($query, string $nationality)
    {
        return $query->where('nationality', $nationality);
    }
}
