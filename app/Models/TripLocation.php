<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripLocation extends Model
{
    /**
     * No updated_at — GPS pings are immutable once written.
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'trip_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'accuracy',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude'    => 'decimal:8',
            'longitude'   => 'decimal:8',
            'speed'       => 'float',
            'heading'     => 'float',
            'accuracy'    => 'float',
            'recorded_at' => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────────────────────────

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    // ── Accessors / helpers ──────────────────────────────────────

    public function getCoordinatesAttribute(): array
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude,
        ];
    }

    /**
     * Serialise to the compact array format used by the Leaflet
     * polyline renderer on the front-end.
     *
     * [[lat, lng], [lat, lng], ...]
     */
    public function toMapPoint(): array
    {
        return [(float) $this->latitude, (float) $this->longitude];
    }

    /**
     * Haversine distance in metres between this ping and another.
     */
    public function distanceTo(self $other): float
    {
        $R    = 6371000;
        $dLat = deg2rad((float) $other->latitude  - (float) $this->latitude);
        $dLng = deg2rad((float) $other->longitude - (float) $this->longitude);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad((float) $this->latitude))
           * cos(deg2rad((float) $other->latitude))
           * sin($dLng / 2) ** 2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}