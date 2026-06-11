<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Mengizinkan penyimpanan path gambar template
    protected $fillable = ['key', 'value'];
}