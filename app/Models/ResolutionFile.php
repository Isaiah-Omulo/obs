<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolutionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'resolution_id',
        'original_name',
        'uploaded_by',
        'path',
    ];

   

    // Uploader
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }


    // ResolutionFile.php
    public function resolution()
    {
        return $this->belongsTo(Resolution::class, 'resolution_id');
    }

}
