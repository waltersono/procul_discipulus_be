<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Lession extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'file_path',
        'subject_id',
        'thematicunit_id',
    ];

    /**
     * Get the thematicunit that owns the lession.
     */
    public function thematic(): BelongsTo
    {
        return $this->belongsTo(Thematicunit::class);
    }

    /**
     * Get the subject that owns the lession.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the summary of the Lession.
     */
    public function summary(): HasMany
    {
        return $this->hasMany(Summary::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function getQuizResults()
    {
        $lession_id = $this->id;

        $quizResults = DB::table('quizzes')
                        ->join('users', 'quizzes.user_id', '=', 'users.id')
                        ->where('quizzes.lession_id', $lession_id)
                        ->whereColumn('quizzes.answer', 'quizzes.correct')
                        ->select('users.name', 'users.surname', DB::raw('SUM(quizzes.score) as total_score'))
                        ->groupBy('users.name','users.surname')
                        ->get();

        return $quizResults;
    }

    /**
     * Get the students for the Lession.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }    

}
