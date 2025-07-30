<?php

// app/Models/File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'occurrence_id', 'path', 'original_name'
    ];

    public function occurrence()
    {
        return $this->belongsTo(Occurrence::class);
    }
}

