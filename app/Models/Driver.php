<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'license_number',
        'license_expiry',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'license_expiry' => 'date',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOnTrip($query)
    {
        return $query->where('status', 'on_trip');
    }

    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    // ── Relationships ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function activeTrip(): HasOne
    {
        return $this->hasOne(Trip::class)->where('status', 'in_progress');
    }

    public function completedTrips(): HasMany
    {
        return $this->hasMany(Trip::class)->where('status', 'completed');
    }

    // ── Accessors ────────────────────────────────────────────────

    /** Delegates to the linked user for convenience */
    public function getNameAttribute(): string
    {
        return $this->user?->name ?? 'Unknown Driver';
    }

    public function getPhoneAttribute(): string
    {
        return $this->user?->phone ?? '—';
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->user?->avatar_url ?? '';
    }

    public function getIsLicenseExpiredAttribute(): bool
    {
        return $this->license_expiry?->isPast() ?? false;
    }

    public function getIsLicenseExpiringSoonAttribute(): bool
    {
        return $this->license_expiry
            && $this->license_expiry->isFuture()
            && $this->license_expiry->diffInDays(now()) <= 30;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'available'  => ['label' => 'Available',  'color' => 'success'],
            'on_trip'    => ['label' => 'On Trip',    'color' => 'info'],
            'off_duty'   => ['label' => 'Off Duty',   'color' => 'secondary'],
            'suspended'  => ['label' => 'Suspended',  'color' => 'danger'],
            default      => ['label' => ucfirst($this->status), 'color' => 'secondary'],
        };
    }

    public function getTotalTripsAttribute(): int
    {
        return $this->trips()->count();
    }
}