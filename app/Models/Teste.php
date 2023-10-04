<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teste extends Model
{
    use HasFactory;

    /**
     * Get the subject that owns the Question.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }
    public function score($student)
    {
        $exercises = $this->exercises()->where('user_id',$student)->get();
        $marks = 0;
        foreach ($exercises as $question) {
            if($question->correct === $question->answer){
                $marks += $question->score;
            }
        }
        return $marks;
    }
    public function afterLession(): BelongsTo
    {
        return $this->belongsTo(Lession::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
}
