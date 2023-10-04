<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
    ];

    /**
     * Get the subject that owns the objective.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    
}
