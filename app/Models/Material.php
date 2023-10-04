<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'subject_id',
        'file_path',
    ];
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
