<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterDailySequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence_date',
        'last_regular_number',
        'insertion_used',
    ];

    protected $casts = [
        'sequence_date' => 'date',
    ];
}
