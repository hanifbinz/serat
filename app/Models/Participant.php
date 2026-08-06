<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    // Pastikan 3 kolom ini diizinkan untuk diisi
    protected $fillable = ['name', 'email', 'phone', 'slug'];

    public function answers()
    {
        return $this->hasMany(ParticipantAnswer::class);
    }
}