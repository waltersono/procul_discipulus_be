<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'acronym',
        'photo',
    ];

    protected $casts = [
        'photo' => 'string',
    ];

    /**
     * Get the courses for the University.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * The users-operator that belong to the schools.
     */
    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
