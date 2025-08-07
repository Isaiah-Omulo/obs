<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverTakeover extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'handover_takeovers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'changeover_type',
        'acting_user_id',
        'involved_user_id',
        'hostel_id',
        'shift',
        'comments',
    ];

    /**
     * Get the hostel where the changeover occurred.
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the user who submitted the form (the one performing the action).
     */
    public function actingUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acting_user_id');
    }

    /**
     * Get the user who was handed over to or taken over from.
     */
    public function involvedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'involved_user_id');
    }
}