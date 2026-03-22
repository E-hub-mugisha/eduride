<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'name',
        'landmark',
        'latitude',
        'longitude',
        'order',
        'arrival_offset_min',
        'dwell_time_sec',
    ];

    protected function casts(): array
    {
        return [
            'latitude'           => 'decimal:8',
            'longitude'          => 'decimal:8',
            'order'              => 'integer',
            'arrival_offset_min' => 'integer',
            'dwell_time_sec'     => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ── Relationships ────────────────────────────────────────────

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /** Students who board at this stop */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getCoordinatesAttribute(): array
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude,
        ];
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->landmark
            ? "{$this->name} ({$this->landmark})"
            : $this->name;
    }

    /**
     * Calculate straight-line distance in metres to a given coordinate.
     * Uses the Haversine formula.
     */
    public function distanceTo(float $lat, float $lng): float
    {
        $earthRadius = 6371000; // metres

        $dLat = deg2rad($lat - (float) $this->latitude);
        $dLng = deg2rad($lng - (float) $this->longitude);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad((float) $this->latitude))
           * cos(deg2rad($lat))
           * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Returns true when a bus at ($lat, $lng) is within $radiusMetres of this stop.
     */
    public function isNearby(float $lat, float $lng, float $radiusMetres = 200): bool
    {
        return $this->distanceTo($lat, $lng) <= $radiusMetres;
    }
}