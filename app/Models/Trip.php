<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'vehicle_id',
        'driver_id',
        'status',
        'current_lat',
        'current_lng'
    ];

    public function route()
    {
        return $this->belongsTo(\App\Models\Route::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class);
    }
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(ParentTripSubscription::class, 'route_id', 'route_id');
    }
}
