<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    // Mengizinkan Admin memasukkan NIM dan Nama secara massal
    protected $fillable = ['nim', 'name'];
}