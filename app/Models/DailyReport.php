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
        'report',
        'report_date',
        'report_time',
        'shift',
        'manager_input',
        'director_input',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
