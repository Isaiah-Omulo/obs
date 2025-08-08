<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyHostelSummary extends Model
{
    use HasFactory;

    // Use a more descriptive table name if you prefer
    protected $table = 'daily_hostel_summaries';

    protected $fillable = [
        'hostel_id',
        'date',
        'capacity',
        'students_present_night',
        'students_present_day',
    ];

    // Cast the date field to a Carbon object for convenience
    protected $casts = [
        'date' => 'date',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}