<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'driver_id',
        'vehicle_id',
        'type',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'current_latitude',
        'current_longitude',
        'current_speed',
        'location_updated_at',
        'delay_minutes',
        'cancellation_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at'        => 'datetime',
            'started_at'          => 'datetime',
            'ended_at'            => 'datetime',
            'location_updated_at' => 'datetime',
            'current_latitude'    => 'decimal:8',
            'current_longitude'   => 'decimal:8',
            'current_speed'       => 'float',
            'delay_minutes'       => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForDriver($query, int $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeForRoute($query, int $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    // ── Relationships ────────────────────────────────────────────

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** All GPS pings for this trip, ordered chronologically */
    public function locations(): HasMany
    {
        return $this->hasMany(TripLocation::class)->orderBy('recorded_at');
    }

    /**
     * Most recent GPS ping.
     * Uses orderByDesc instead of latestOfMany() — trip_locations has no
     * created_at column and latestOfMany() always appends it as a tiebreaker.
     */
    public function latestLocation(): HasOne
    {
        return $this->hasOne(TripLocation::class)
                    ->orderByDesc('recorded_at');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(TransportNotification::class);
    }

    // ── State helpers ────────────────────────────────────────────

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Start the trip: set status, mark driver as on_trip.
     */
    public function start(): void
    {
        $this->update([
            'status'     => 'in_progress',
            'started_at' => now(),
        ]);

        $this->driver->update(['status' => 'on_trip']);
    }

    /**
     * End the trip: set status, compute duration, free the driver.
     */
    public function end(): void
    {
        $this->update([
            'status'   => 'completed',
            'ended_at' => now(),
        ]);

        $this->driver->update(['status' => 'available']);
    }

    /**
     * Update the denormalised current location on the trip row.
     * Called every time a GPS ping arrives so we avoid a JOIN
     * when the admin map just needs "where is each bus now".
     */
    public function updateLocation(float $lat, float $lng, float $speed = 0, float $heading = 0): void
    {
        $this->update([
            'current_latitude'    => $lat,
            'current_longitude'   => $lng,
            'current_speed'       => $speed,
            'location_updated_at' => now(),
        ]);
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getDurationAttribute(): ?string
    {
        if (! $this->started_at || ! $this->ended_at) return null;

        $minutes = $this->started_at->diffInMinutes($this->ended_at);

        return sprintf('%dh %02dm', intdiv($minutes, 60), $minutes % 60);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'scheduled'   => ['label' => 'Scheduled',    'color' => 'secondary'],
            'in_progress' => ['label' => 'In Progress',  'color' => 'info'],
            'completed'   => ['label' => 'Completed',    'color' => 'success'],
            'cancelled'   => ['label' => 'Cancelled',    'color' => 'danger'],
            'delayed'     => ['label' => 'Delayed',      'color' => 'warning'],
            default       => ['label' => ucfirst($this->status), 'color' => 'secondary'],
        };
    }

    public function getCurrentCoordinatesAttribute(): ?array
    {
        if (! $this->current_latitude || ! $this->current_longitude) return null;

        return [
            'lat' => (float) $this->current_latitude,
            'lng' => (float) $this->current_longitude,
        ];
    }

    /** Returns true if the last GPS ping is older than 2 minutes (stale). */
    public function getIsLocationStaleAttribute(): bool
    {
        if (! $this->location_updated_at) return true;

        return $this->location_updated_at->diffInSeconds(now()) > 120;
    }
}