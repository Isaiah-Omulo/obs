<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['name'];

    public function hostels()
    {
        return $this->hasMany(Hostel::class);
    }
}
