<?php

// app/Models/Occurrence.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    protected $fillable = [
        'user_id', 'shift','hostel', 'date', 'time', 'nature','occurrence_type',
        'action_taken', 'resolution','resolved', 'manager_input', 'director_input'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
