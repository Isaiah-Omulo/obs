<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentStatistic extends Model
{
    protected $fillable = [
        'user_id',
        'hostel_id',
        'record_date',
        'shift',
        'students_present',
        'comments',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // In StudentStatistic.php
    public static function lastUsedHostelIdByUser($userId)
    {
        return self::where('user_id', $userId)->latest()->value('hostel_id');
    }
}