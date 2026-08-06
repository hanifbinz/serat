<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'registration_field_id',
        'answer_value',
    ];

    /**
     * Relasi ke RegistrationField (Menghubungkan jawaban dengan label pertanyaannya)
     */
    public function field()
    {
        return $this->belongsTo(RegistrationField::class, 'registration_field_id');
    }
}