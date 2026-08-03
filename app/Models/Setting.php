<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    // Helper untuk mengambil setting dengan mudah
    // Menambahkan "string" pada $key dan "mixed" pada $default untuk mengatasi warning Intelephense
    public static function getValue(string $key, mixed $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}