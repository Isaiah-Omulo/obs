<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalationMatrix extends Model
{
    use HasFactory;

    protected $table = 'escalation_matrix';

    protected $fillable = [
        'department_name',
        'email',
    ];
}
