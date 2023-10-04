<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thematicunit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'time',
        'subject_id',
    ];

    /**
     * Get the subject that owns the Thematicnit.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the lessions for the University.
     */
    public function lessions(): HasMany
    {
        return $this->hasMany(Lession::class);
    }
}
