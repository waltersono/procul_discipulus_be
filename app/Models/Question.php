<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'lession_id',
        'subject_id',
        'question',
        'optionA',
        'optionB',
        'optionC',
        'optionD',
        'correct',
        'score',
    ];

    /**
     * Get the subject that owns the Question.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    /**
     * Get the subject that owns the Question.
     */
    public function lession(): BelongsTo
    {
        return $this->belongsTo(Lession::class);
    }
}
