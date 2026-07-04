<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterNumberSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_type_id',
        'bidang',
        'month',
        'year',
        'last_number',
    ];

    public function letterType()
    {
        return $this->belongsTo(LetterType::class);
    }
}
