<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
    'hostel_id',
    'zone_id',
    'date',
    'time',
    'is_water',
    'amount',
    'is_hot_water',
    'remarks',
    'user_id',
];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
