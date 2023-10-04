<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'teste_id',
        'question',
        'optionA',
        'optionB',
        'optionC',
        'optionD',
        'correct'
    ];
    public function teste(): BelongsTo
    {
        return $this->belongsTo(Teste::class);
    }
}
