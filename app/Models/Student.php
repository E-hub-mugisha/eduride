<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'route_id',
    ];
    
    public function parents()
    {
        return $this->belongsToMany(\App\Models\User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function route()
    {
        return $this->belongsTo(\App\Models\Route::class);
    }
}
