<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plate_number',
        'model',
        'brand',
        'color',
        'capacity',
        'year_manufactured',
        'status',
        'photo',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'capacity'          => 'integer',
            'year_manufactured' => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                     ->whereDoesntHave('activeTrip');
    }

    // ── Relationships ────────────────────────────────────────────

    /** The driver currently assigned to this vehicle */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function activeTrip(): HasOne
    {
        return $this->hasOne(Trip::class)->where('status', 'in_progress');
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('assets/img/default-bus.png');
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->brand} {$this->model} · {$this->plate_number}";
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'active'      => ['label' => 'Active',       'color' => 'success'],
            'maintenance' => ['label' => 'Maintenance',  'color' => 'warning'],
            'inactive'    => ['label' => 'Inactive',     'color' => 'danger'],
            default       => ['label' => ucfirst($this->status), 'color' => 'secondary'],
        };
    }

    public function getIsOnTripAttribute(): bool
    {
        return $this->activeTrip()->exists();
    }
}