<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_point',
        'end_point',
        'stops',
        'vehicle_id',
        'driver_id',
        'start_time',
        'end_time',
        'status'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function getStopsArrayAttributes()
    {
        return $this->stops ? array_map('trim', explode(',', $this->stops)) : [];
    }
}
