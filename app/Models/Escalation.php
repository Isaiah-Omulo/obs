<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Occurrence;
use App\Models\User;

class Escalation extends Model
{
    protected $fillable = [
        'occurrence_id',
        'recipient_email',
        'subject',
        'message',
        'user_id'
    ];

    public function occurrence()
    {
        return $this->belongsTo(Occurrence::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
