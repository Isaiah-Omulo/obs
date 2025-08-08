<?php

// app/Models/Occurrence.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    protected $fillable = [
    'user_id', 'shift','hostel', 'date', 'time_of_reporting', 'nature','occurrence_type','action_taken', 'resolution','resolved', 'zonal_officer_input', 'administrator_input', 'manager_input', 'director_input', 'hostel_input','time_of_occurrence','tracking_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }


    public static function lastUsedHostelByUser($userId)
    {
        return self::where('user_id', $userId)->latest()->value('hostel');
    }

}
