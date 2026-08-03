<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Nama pertanyaan (misal: "Asal Instansi")
            $table->enum('type', ['text', 'number'])->default('text'); // Tipe inputan
            $table->boolean('is_required')->default(true); // Wajib diisi atau tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_fields');
    }
};