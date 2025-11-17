<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'occurrence_id',
        'description',
        'resolved_by',
        'resolution_date',
        'resolution_time',
        'comments',
    ];

    // A resolution can have many files
    public function files()
    {
        return $this->hasMany(ResolutionFile::class);
    }

    // User who resolved
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by')->withTrashed();
    }


    // Resolution.php
public function occurrence()
{
    return $this->belongsTo(Occurrence::class, 'occurrence_id');
}


}
