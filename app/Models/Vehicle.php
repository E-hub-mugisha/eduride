<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'model',
        'capacity',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
