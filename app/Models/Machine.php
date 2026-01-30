<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Machine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'machine_token',
        'name',
        'area',
        'place',
        'serial_number',
        'status',
        'ip_address',
        'user_agent',
        'device_type',
        'last_seen_at',
        'last_heartbeat_at',
    ];

    protected $casts = [
        'last_heartbeat_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function imeiChecks(): HasMany
    {
        return $this->hasMany(ImeiCheck::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    public function updateHeartbeat(): void
    {
        $this->update(['last_heartbeat_at' => now()]);
    }

    public static function findOrCreateByToken(?string $token, string $ipAddress, ?string $userAgent = null): array
    {
        $isNew = false;
        $machine = null;

        // First try to find by token (cookie-based)
        if ($token) {
            $machine = self::where('machine_token', $token)->first();
        }

        if (!$machine) {
            // Create new machine with new token
            $isNew = true;
            $newToken = Str::uuid()->toString();
            $machine = self::create([
                'machine_token' => $newToken,
                'name' => 'Kiosk-' . substr(md5($newToken), 0, 6),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'device_type' => self::detectDeviceType($userAgent),
                'status' => 'active',
                'last_seen_at' => now(),
            ]);
        } else {
            $machine->update([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'last_seen_at' => now(),
            ]);
        }

        return ['machine' => $machine, 'isNew' => $isNew];
    }

    protected static function detectDeviceType(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'unknown';
        }

        $userAgent = strtolower($userAgent);

        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android') || str_contains($userAgent, 'iphone')) {
            return 'mobile';
        }

        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }

        return 'desktop';
    }

    public function scopeByIp($query, string $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }
}
