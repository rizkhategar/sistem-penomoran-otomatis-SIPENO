<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterType extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_bidang_id',
        'master_jenis_surat_id',
        'name',
        'code',
        'bidang',
        'created_by',
        'description',
        'monthly_quota',
        'daily_insertion',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function masterBidang()
    {
        return $this->belongsTo(MasterBidang::class, 'master_bidang_id');
    }

    public function masterJenisSurat()
    {
        return $this->belongsTo(MasterJenisSurat::class, 'master_jenis_surat_id');
    }

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
