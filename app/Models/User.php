<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role helpers ─────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeDriverUsers($query)
    {
        return $query->where('role', 'driver');
    }

    public function scopeParents($query)
    {
        return $query->where('role', 'parent');
    }

    // ── Relationships ────────────────────────────────────────────

    /** Driver profile (only when role = driver) */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /** Children registered under this parent account */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /** In-app transport notifications */
    public function notifications(): HasMany
    {
        return $this->hasMany(TransportNotification::class);
    }

    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->where('is_read', false);
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=00E5C3&color=050B18&bold=true';
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin'  => 'Admin',
            'driver' => 'Driver',
            'parent' => 'Parent',
            default  => ucfirst($this->role),
        };
    }
}