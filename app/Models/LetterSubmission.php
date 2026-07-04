<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'letter_type_id',
        'keperluan',
        'file_path',
        'status',
        'letter_number',
        'is_sk',
        'submission_date',
        'alasan_penolakan',
        'approved_by',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'submission_date' => 'date',
        'is_sk' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function letterType()
    {
        return $this->belongsTo(LetterType::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
