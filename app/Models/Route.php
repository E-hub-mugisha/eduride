<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'morning_departure',
        'afternoon_departure',
        'estimated_duration_min',
        'total_distance_km',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active'              => 'boolean',
            'estimated_duration_min' => 'integer',
            'total_distance_km'      => 'decimal:2',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMorning($query)
    {
        return $query->whereIn('type', ['morning', 'both']);
    }

    public function scopeAfternoon($query)
    {
        return $query->whereIn('type', ['afternoon', 'both']);
    }

    // ── Relationships ────────────────────────────────────────────

    /** Ordered stops along this route */
    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class)->orderBy('order');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class)->latest();
    }

    public function activeTrip(): ?Trip
    {
        return $this->hasOne(Trip::class)->where('status', 'in_progress')->first();
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getStopCountAttribute(): int
    {
        return $this->stops()->count();
    }

    public function getStudentCountAttribute(): int
    {
        return $this->students()->where('is_active', true)->count();
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'morning'   => 'Morning only',
            'afternoon' => 'Afternoon only',
            'both'      => 'Morning & Afternoon',
            default     => ucfirst($this->type),
        };
    }

    /**
     * First and last stop names as a short summary.
     * e.g. "Kicukiro → IRERERO Academy"
     */
    public function getPathSummaryAttribute(): string
    {
        $stops = $this->stops;
        if ($stops->isEmpty()) return '—';

        return $stops->first()->name . ' → ' . $stops->last()->name;
    }
}