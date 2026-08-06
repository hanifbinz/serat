<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Mengubah kolom nim agar boleh kosong (nullable)
            $table->string('nim')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Mengembalikan nim menjadi wajib isi jika di-rollback
            $table->string('nim')->nullable(false)->change();
        });
    }
};