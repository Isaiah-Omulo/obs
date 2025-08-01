<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = ['name', 'number_of_students', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function studentStatistics(){
        return $this->hasMany(StudentStatistic::class);
    }
}
