<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'type',
        'is_required',
    ];

    public function answers()
    {
        return $this->hasMany(ParticipantAnswer::class);
    }
}