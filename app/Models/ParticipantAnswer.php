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

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function registrationField()
    {
        return $this->belongsTo(RegistrationField::class);
    }
}