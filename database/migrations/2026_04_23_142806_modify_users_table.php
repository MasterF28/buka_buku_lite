<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahin kolom baru
            $table->string('nim')->unique()->after('email');
            $table->enum('role', ['admin','mahasiswa'])
                  ->default('mahasiswa')
                  ->after('nim');

            // Optional: kalau mau hapus email & password (karena login pakai NIM)
            // HATI-HATI kalau sudah dipakai auth bawaan Laravel
            // $table->dropColumn(['email', 'password']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'role']);
        });
    }
};