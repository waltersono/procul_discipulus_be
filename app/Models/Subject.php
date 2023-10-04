<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'semester',
        'type',
        'time',
        'credits',
        'course_id'
    ];
    /**
     * Get the course that owns the subject.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the objectives for the Subject.
     */
    public function objectives(): HasMany
    {
        return $this->hasMany(Objective::class);
    }

    /**
     * Get the thematic units for the Subject.
     */
    public function thematics(): HasMany
    {
        return $this->hasMany(Thematicunit::class);
    }

    /**
     * Get the lessions for the Subject.
     */
    public function lessions(): HasMany
    {
        return $this->hasMany(Lession::class);
    }

    /**
     * Get the students for the Subject.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the materials for the Subject.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get the testes for the Subject.
     */
    public function testes(): HasMany
    {
        return $this->hasMany(Teste::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
