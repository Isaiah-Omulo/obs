<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OccurrenceInput extends Model
{
    protected $table = 'occurrence_inputs';

    // Allow mass assignment
    protected $fillable = [
        'occurrence_id',
        'user_id',
        'role',
        'input_text',
        'parent_id',
    ];

    /**
     * The occurrence this input belongs to.
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(Occurrence::class);
    }

    /**
     * The user who created this input.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Parent input (if this is a reply)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OccurrenceInput::class, 'parent_id');
    }

    /**
     * Replies to this input
     */
    public function replies(): HasMany
    {
        return $this->hasMany(OccurrenceInput::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
