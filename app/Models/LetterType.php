<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'bidang',
        'created_by',
        'description',
        'monthly_quota',
        'daily_insertion',
        'is_active',
    ];

    public function submissions()
    {
        return $this->hasMany(LetterSubmission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function numberSequences()
    {
        return $this->hasMany(LetterNumberSequence::class);
    }
}
