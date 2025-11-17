<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zone',
        'hostel_id',
        'report',
        'report_date',
        'report_time',
        'shift',
        'manager_input',
        'director_input',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public static function lastUsedZoneByUser($userId) {
        return self::where('user_id', $userId)->latest()->value('zone');
    }

    public static function lastUsedHostelByUser($userId)
    {
        return self::where('user_id', $userId)
            ->whereNotNull('hostel_id')
            ->latest('created_at')
            ->value('hostel_id');
    }

    // DailyReport.php
public function hostel()
{
    return $this->belongsTo(\App\Models\Hostel::class, 'hostel_id');
}


}
