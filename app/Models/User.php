<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'driver_id');
    }
    public function routes()
    {
        return $this->hasMany(Route::class, 'driver_id');
    }
    public function students()
    {
        return $this->belongsToMany(\App\Models\Student::class, 'parent_student', 'parent_id', 'student_id');
    }

    public function tripSubscriptions()
    {
        return $this->hasMany(\App\Models\ParentTripSubscription::class, 'parent_id');
    }
}
