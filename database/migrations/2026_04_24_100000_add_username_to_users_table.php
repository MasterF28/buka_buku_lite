<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('role');
            $table->string('npm')->nullable()->unique()->after('nim');
            $table->string('nim')->nullable()->change();
        });

        // Update existing users with default password before making it non-nullable
        DB::table('users')->whereNull('password')->update([
            'password' => Hash::make('password123')
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->dropColumn(['username', 'npm']);
        });
    }
};

