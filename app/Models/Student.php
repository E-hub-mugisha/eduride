<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'route_id',
        'stop_id',
        'full_name',
        'student_id',
        'grade',
        'class_section',
        'date_of_birth',
        'photo',
        'is_active',
        'medical_notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active'     => 'boolean',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForRoute($query, int $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    // ── Relationships ────────────────────────────────────────────

    /** The parent user account */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /** The stop where this student boards/alights */
    public function stop(): BelongsTo
    {
        return $this->belongsTo(Stop::class);
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=00E5C3&color=050B18&bold=true';
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    public function getDisplayGradeAttribute(): string
    {
        return $this->grade && $this->class_section
            ? "{$this->grade} – {$this->class_section}"
            : ($this->grade ?? '—');
    }

    /** Parent's name for display in admin tables */
    public function getParentNameAttribute(): string
    {
        return $this->user?->name ?? '—';
    }

    public function getParentPhoneAttribute(): string
    {
        return $this->user?->phone ?? '—';
    }
}