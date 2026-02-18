<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentTripSubscription extends Model
{
    protected $fillable = ['parent_id', 'route_id', 'child_id', 'stop_name'];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class, 'child_id');
    }

    public function route()
    {
        return $this->belongsTo(\App\Models\Route::class);
    }
    public function parent()
    {
        return $this->belongsTo(\App\Models\User::class, 'parent_id');
    }
}
