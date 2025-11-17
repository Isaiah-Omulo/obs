<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- Add this line
use Illuminate\Database\Eloquent\Relations\HasMany;


class Hostel extends Model
{
    protected $fillable = ['name', 'number_of_students', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function studentStatistics(): HasMany
    {
        return $this->hasMany(StudentStatistic::class);
    }


  
    public function latestStudentStatistic(): HasOne
    {
        return $this->hasOne(StudentStatistic::class)->latestOfMany();
    }

      public function waterMonitorings(): HasMany
    {
        return $this->hasMany(WaterMonitoring::class);
    }
}
